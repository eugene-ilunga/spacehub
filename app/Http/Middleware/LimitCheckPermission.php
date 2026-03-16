<?php

namespace App\Http\Middleware;

use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Admin;
use App\Models\ClientService\Form;
use App\Models\ClientService\Service;
use App\Models\Language;
use App\Models\QRCode;
use App\Models\Space;
use App\Models\SpaceContent;
use App\Models\SpaceService;
use App\Models\SubService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LimitCheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */


  public function handle(Request $request, Closure $next, $type = null)
  {

$languages = Language::select('code')->get();

    // Check if the user is an admin
    if ($request->input('seller_id') == 'admin') {
      $seller_id = 0 ;
    }
    elseif ($request->input('seller_id') !== 'admin'){
      $seller_id = $request->input('seller_id');
    }
    else {

      $seller_id = Auth::guard('seller')->user()->id;
    }
    if ($seller_id == 0) {
      return $next($request);
    }
    

    $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
 
    if ($currentPackage) {
      $space_type = [];
      $features = json_decode($currentPackage->package_feature, true);
      if (in_array('Fixed Timeslot Rental', $features)) {
        $space_type[] = 1;
      }
      if (in_array('Hourly Rental', $features)) {
        $space_type[] = 2;
      }
      if (in_array('Multi Day Rental', $features)) {
        $space_type[] = 3;
      }

      switch ($type) {
        case 'space':
          $total_space = Space::where('seller_id', $seller_id)
          ->whereIn('space_type', $space_type)
          ->count();
          if ($currentPackage->number_of_space != 999999 && ($total_space >= $currentPackage->number_of_space)) {

            return response()->json(['status' => 'error', 'message' => __('Your space limit is exceeded') . '. ' .__('You can add up to') .' ' . $currentPackage->number_of_space . ' '.__('spaces') . '.']);

          }

          $total_amenities_per_space = [];
          foreach ($languages as $language) {
            $key = $language->code . '_amenities';
            if ($request->has($key)) {
              $value = $request->input($key);
              if (is_array($value)) {
                $total_amenities_per_space[$language->code] = count($value);
              } else {
                $total_amenities_per_space[$language->code] = 0;
              }
            } else {
              $total_amenities_per_space[$language->code] = 0;
            }
          }

          foreach ($total_amenities_per_space as $language_code => $total_amenities) {
            if ($currentPackage->number_of_amenities_per_space != 999999 && ($total_amenities > $currentPackage->number_of_amenities_per_space)) {
              return response()->json([
                'status' => 'error',
                'message' => __('Your'). ' ' . $language_code . ' '. __('amenities per space limit is exceeded'). '. ' . __('You can add up to'). ' ' . $currentPackage->number_of_amenities_per_space .' '  . __('amenities per space') . ' .'
              ]);
            }
          }

          // Get the new slider images from the request
          $total_slider_image_per_space = 0;
          $new_slider_images = $request->input('slider_images', []);
          $new_slider_image_per_space = count($new_slider_images);
          $space_id = $request->input('space_id');
          $space =Space::find($space_id);
          if($space)
          {
            $slider_images = json_decode($space->slider_images, true);
            $existing_slider_image_per_space = count($slider_images);
          }
          else{
            $existing_slider_image_per_space = 0;
          }

          $total_slider_image_per_space = $new_slider_image_per_space + $existing_slider_image_per_space;

          if ($currentPackage->number_of_slider_image_per_space != 999999 && ($total_slider_image_per_space > $currentPackage->number_of_slider_image_per_space)) {

            return response()->json(['status' => 'error', 'message' => __('Your slider image per space limit is exceeded') . '.  '. __('You can add up to') . ' ' . $currentPackage->number_of_slider_image_per_space . ' ' . __('slider images per space') . '.']);
          }

          break;

        case 'service-per-space':
          $space_id = $request->input('space_id');
          
          $total_service_per_space = SpaceService::where('space_id', $space_id)->count();
          if ($currentPackage->number_of_service_per_space != 999999 && ($total_service_per_space >= $currentPackage->number_of_service_per_space)) {
            return response()->json(['status' => 'error', 'message' => __('Your service per space limit is exceeded') . '. ' . __('You can add up to') . ' ' . $currentPackage->number_of_service_per_space . ' ' . __('services per space'). '.']);
          }

          // Additional check for subservices (options)
          $total_subservices = 0;
          foreach ($request->all() as $key => $value) {
            $sub_total_subservices = 0;
            if (strpos($key, '_sub_service_name') !== false) {
              $sub_total_subservices += count($value);
              $total_subservices = $sub_total_subservices;
            }
          }
          // Get the existing subservices count for the service being edited
          $service_id = $request->input('service_id');
          if ($service_id) {
            $existing_subservices_count = SubService::where('service_id', $service_id)->count();
          } else {
            $existing_subservices_count = 0;
          }

          $total_subservices += $existing_subservices_count;

          if ($currentPackage->number_of_option_per_service != 999999 && ($total_subservices > $currentPackage->number_of_option_per_service)) {
            return response()->json(['status' => 'error', 'message' => __('Your option per service limit is exceeded'). '. ' . __('You can add up to') . ' ' .$currentPackage->number_of_option_per_service . ' ' . __('options per service') . '.']);
          }
          break;


        default:
          break;
      }
    }
    else {
      Session::flash('warning', __('Please purchase a new package or extend your current package') . '.');
      return response()->json(['status' => 'success'], 200);
    }

    return $next($request);
  }


}
