<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Follower;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceCategory;
use App\Models\SpaceContent;
use App\Models\SpaceReview;
use App\Models\SpaceWishlist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{

    public function index(Request $request)
    {

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('vendor_page_meta_keywords as meta_keywords', 'vendor_page_meta_description as meta_description')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();
        $name = $location = null;
        $sellerIds = [];
        if ($request->filled('name')) {
            $name = $request->name;
            $u_infos = Seller::where('username', 'like', '%' . $name . '%')->get();
            $s_infos = SellerInfo::where([['seller_infos.name', 'like', '%' . $name . '%'], ['language_id', $language->id]])->get();

            foreach ($u_infos as $info) {
                if (!in_array($info->id, $sellerIds)) {
                    array_push($sellerIds, $info->id);
                }
            }
            foreach ($s_infos as $s_info) {
                if (!in_array($s_info->seller_id, $sellerIds)) {
                    array_push($sellerIds, $s_info->seller_id);
                }
            }
        }

        if ($request->filled('location')) {
            $location = $request->location;
        }

        if ($request->filled('location')) {
            $seller_contents = SellerInfo::where('country', 'like', '%' . $location . '%')
                ->orWhere('city', 'like', '%' . $location . '%')
                ->orWhere('state', 'like', '%' . $location . '%')
                ->orWhere('zip_code', 'like', '%' . $location . '%')
                ->orWhere('address', 'like', '%' . $location . '%')
                ->get();
            foreach ($seller_contents as $seller_content) {
                if (!in_array($seller_content->seller_id, $sellerIds)) {
                    array_push($sellerIds, $seller_content->seller_id);
                }
            }
        }

        $admin = Admin::select('admins.*')
            ->when($name, function ($query) use ($name) {
                return $query->where('username', 'like', '%' . $name . '%');
            })
            ->when($location, function ($query) use ($location) {
                return $query->where('address', 'like', '%' . $location . '%');
            })
            ->first();

        $sellers = Seller::where([
            ['sellers.status', 1],
            ['sellers.id', '!=', 0],
        ])
            ->join('memberships', 'memberships.seller_id', 'sellers.id')
            ->where([
                ['memberships.status', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
            ])
            ->when($name, function ($query) use ($sellerIds) {
                return $query->whereIn('sellers.id', $sellerIds);
            })
            ->when($location, function ($query) use ($sellerIds) {
                return $query->whereIn('sellers.id', $sellerIds);
            })
            ->select('sellers.*', 'sellers.id as sellerId', 'memberships.*')
            ->orderBy('sellers.id', 'asc');

        if ($admin) {
            $queryResult['totalSellers'] = $sellers->count() + 1;
        } else {
            $queryResult['totalSellers'] = $sellers->count();
        }

        $queryResult['sellers'] = $sellers->paginate(10);

        $queryResult['admin'] = $admin;

        return view('frontend.seller.index', $queryResult);
    }


    public function details(Request $request)
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $queryResult['seoInfo'] = $language->seoInfo()->select('vendor_details_page_meta_keywords as meta_keywords', 'vendor_details_page_meta_description as meta_description')->first();

        $data['currencyInfo'] = $this->getCurrencyInfo();
        $queryResult['language'] = $language;

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();

        $basicInfo = Basic::query()->select('admin_profile')->first();

        if ($request->admin == true && $basicInfo->admin_profile == 1) {
            $seller = Admin::first();
            $seller_id = 0;
            $queryResult['total_service'] = Space::where('seller_id', 0)->count();
        } else {
            $seller = Seller::join('memberships', 'memberships.seller_id', 'sellers.id')
                ->where([
                    ['memberships.status', 1],
                    ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                    ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
                ])
                ->where('sellers.username', $request->username)
                ->select('sellers.*')
                ->firstOrFail();
            $sellerInfo = SellerInfo::where([['seller_id', $seller->id], ['language_id', $language->id]])->first();
            $queryResult['sellerInfo'] = $sellerInfo;
            $seller_id = $seller->id;
        }
        $queryResult['seller'] = $seller;
        // Call the combined function to retrieve space IDs according to package feature of vendor
        $spaceIds = Package::getSpaceIdsBySeller($seller_id);

        // Get the space_category_id values from SpaceContent

        $categoryIds = SpaceContent::leftJoin('spaces', 'spaces.id', '=', 'space_contents.space_id')
            ->where([
                ['spaces.seller_id', $seller_id],
                ['space_contents.language_id', $language->id],
            ])
            ->whereIn('spaces.id', $spaceIds)
            ->distinct()
            ->pluck('space_category_id')
            ->toArray();

        // Query SpaceCategory with the extracted IDs
        $queryResult['categories'] = SpaceCategory::whereIn('id', $categoryIds)
            ->where([
                ['language_id', $language->id],
                ['status', 1]
            ])
            ->select('name', 'id', 'slug')
            ->get();

        $spaces = Space::query()->select(
            'spaces.id as space_id',
            'spaces.space_rent',
            'spaces.rent_per_hour',
            'spaces.price_per_day',
            'spaces.latitude',
            'spaces.longitude',
            'spaces.average_rating',
            'spaces.seller_id',
            'spaces.thumbnail_image as image',
            'spaces.max_guest',
            'spaces.min_guest',
            'spaces.use_slot_rent',
            'spaces.space_status as status',
            'space_contents.id as space_content_id',
            'space_contents.title',
            'space_contents.slug',
            'space_contents.space_category_id',
            'space_contents.address',
            'space_categories.id as category_id',
            'space_categories.name as category_title',
            'space_categories.slug as category_slug',
            'countries.id as country_id',
            'countries.name as country_name',
            'cities.id as city_id',
            'cities.name as city_name',
            'states.id as state_id',
            'states.name as state_name',
            'sellers.id as seller_id',
            'sellers.photo as seller_image',
            'sellers.username'
        )
            ->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
            ->leftJoin('space_categories', 'space_contents.space_category_id', '=', 'space_categories.id')
            ->leftJoin('countries', 'space_contents.country_id', '=', 'countries.id')
            ->leftJoin('cities', 'space_contents.city_id', '=', 'cities.id')
            ->leftJoin('states', 'space_contents.state_id', '=', 'states.id')
            ->leftJoin('sellers', 'spaces.seller_id', '=', 'sellers.id')
            ->where([
                ['spaces.space_status', '=', 1],
                ['space_contents.language_id', '=', $language->id],
                ['spaces.seller_id', $seller_id],
                ['space_categories.status', '=', 1],
            ])
            ->whereIn('spaces.id', $spaceIds)
            ->orderBy('spaces.id', 'desc')
            ->get();

        // review
        $spaces->map(function ($space) {
            $space['reviewCount'] = SpaceReview::where('space_id', $space->space_id)->count();
        });


        // wishlist
        if (Auth::guard('web')->check() == true) {
            $spaces->map(function ($space) {
                $authUser = Auth::guard('web')->user();

                $listedSpace = SpaceWishlist::query()->where([['user_id', $authUser->id], ['space_id', $space->space_id]])->first();

                if (empty($listedSpace)) {
                    $space['wishlisted'] = false;
                } else {
                    $space['wishlisted'] = true;
                }
            });
        }

        $queryResult['totalSpaces'] = Space::where([
            ['spaces.space_status', '=', 1],
            ['spaces.seller_id', $seller_id]
        ])->get();
        $queryResult['spaces'] = $spaces;

        $queryResult['numberOfBooking'] = SpaceBooking::where('booking_status', 'approved')
            ->where('seller_id', $seller_id != 0 ? $seller_id : null)
            ->count();

        $queryResult['currencyInfo'] = $this->getCurrencyInfo();
        $queryResult['languageId'] = $language->id;
        $queryResult['spaceIds'] = $spaceIds;
        $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'to_mail')->first();

        return view('frontend.seller.details', $queryResult);
    }

    //contact
    public function contact(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];
        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messageArray = [];

        if ($info->google_recaptcha_status == 1) {
            $messageArray['g-recaptcha-response.required'] = __('Please verify that you are not a robot') . '.';
            $messageArray['g-recaptcha-response.captcha'] = __('Captcha error! try again later or contact site admin') . '.';
        }

        $validator = Validator::make($request->all(), $rules, $messageArray);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 400);
        }


        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')->firstOrFail();

        $c_message = nl2br($request->message);
        $msg = __("You have received a new message from a customer") . "<br><br>
                <h4>" . __('Name') . ": " . $request->name . "</h4>
                <h4>" . __('Email') . ": " . $request->email . "</h4>
                <p>" . __('Message') . ":</p>
                <p>$c_message</p>";

        $data = [
            'to' => $request->seller_email,
            'subject' => $request->subject,
            'message' => $msg,
        ];

        if ($be->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $be->smtp_host,
                    'port' => $be->smtp_port,
                    'encryption' => $be->encryption,
                    'username' => $be->smtp_username,
                    'password' => $be->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                return back();
            }
        }
        try {
            if ($be->smtp_status == 1) {
                Mail::send([], [], function (Message $message) use ($data, $be, $request) {
                    $fromMail = $be->from_mail;
                    $fromName = $be->from_name;
                    $message->to($data['to'])
                        ->subject($data['subject'])
                        ->from($fromMail, $fromName)
                        ->replyTo($request->email, $request->name)
                        ->html($data['message'], 'text/html');
                });
            }
            Session::flash('success', __('Message sent successfully') . '.');
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', __('Something went wrong') . '.');
            return redirect()->back();
        }
    }

}
