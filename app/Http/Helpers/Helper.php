<?php

use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\Space;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Package;
use Carbon\Carbon;

if (!function_exists('createSlug')) {
  function createSlug($string)
  {
    $slug = preg_replace('/\s+/u', '-', trim($string));
    $slug = str_replace('/', '', $slug);
    $slug = str_replace('?', '', $slug);
    $slug = str_replace(',', '', $slug);
    $slug = str_replace('&', '', $slug);

    return mb_strtolower($slug);
  }
}

if (!function_exists('replaceBaseUrl')) {
  function replaceBaseUrl($html, $type)
  {
    $startDelimiter = 'src=""';

    if ($type == 'summernote') {
      $endDelimiter = '/assets/img/summernote';
    } elseif ($type == 'pagebuilder') {
      $endDelimiter = '/assets/img';
    }

    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;

    while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
      $contentStart += $startDelimiterLength;
      $contentEnd = strpos($html, $endDelimiter, $contentStart);

      if (false === $contentEnd) {
        break;
      }

      $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
      $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $html;
  }
}

if (!function_exists('setEnvironmentValue')) {
  function setEnvironmentValue(array $values)
  {
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
      foreach ($values as $envKey => $envValue) {
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, "\n", $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

        // If key does not exist, add it
        if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
          $str .= "{$envKey}={$envValue}\n";
        } else {
          $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        }
      }
    }

    $str = substr($str, 0, -1);

    if (!file_put_contents($envFile, $str)) return false;

    return true;
  }
}

if (!function_exists('showAd')) {
  function showAd($resolutionType)
  {
    $ad = Advertisement::query()->where('resolution_type', '=', $resolutionType)->inRandomOrder()->first();
    $googleAdsensePublisherId = Basic::query()->pluck('google_adsense_publisher_id')->first();

    if (!is_null($ad)) {
      if ($resolutionType == 1) {
        $maxWidth = '300px';
        $maxHeight = '250px';
      } else if ($resolutionType == 2) {
        $maxWidth = '300px';
        $maxHeight = '600px';
      } elseif ($resolutionType == 3) {
        $maxWidth = '728px';
        $maxHeight = '90px';
      } elseif ($resolutionType == 4) {
        $maxWidth = '370px';
        $maxHeight = '250px';
      } else {
        $maxWidth = '370px';
        $maxHeight = '600px';
      }

      if ($ad->ad_type == 'banner') {
        $markUp = '<a href="' . url($ad->url) . '" target="_blank" onclick="adView(' . $ad->id . ')">
          <img  data-src="' . asset('assets/img/advertisements/' . $ad->image) . '" alt="advertisement" style="width: ' . $maxWidth . '; max-height: ' . $maxHeight . ';" class="lazyload">
        </a>';

        return $markUp;
      } else {
        $markUp = '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . $googleAdsensePublisherId . '" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display: block;" data-ad-client="' . $googleAdsensePublisherId . '" data-ad-slot="' . $ad->slot . '" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';

        return $markUp;
      }
    } else {
      return;
    }
  }
}

if (!function_exists('get_href')) {
  function get_href($data)
  {

    $link_href = '';

    if ($data->type == 'home') {
      $link_href = route('index');
    } else if ($data->type == 'spaces') {
      $link_href = route('space.index');
    } else if ($data->type == 'vendors') {
      $link_href = route('frontend.sellers');
    } else if ($data->type == 'shop') {
      $link_href = '#';
    } else if ($data->type == 'product') {
      $link_href = route('shop.products');
    } else if ($data->type == 'cart') {
      $link_href = route('shop.cart');
    } else if ($data->type == 'checkout') {
      $link_href = route('shop.checkout');
    } else if ($data->type == 'blog') {
      $link_href = route('blog');
    } else if ($data->type == 'about-us') {
      $link_href = route('about_us');
    } else if ($data->type == 'faq') {
      $link_href = route('faq');
    } else if ($data->type == 'contact') {
      $link_href = route('contact');
    } else if ($data->type == 'pricing') {
      $link_href = route('pricing');
    } else if ($data->type == 'pages') {
      $link_href = '#';
    } else if ($data->type == 'custom') {
      /**
       * this menu has created using menu-builder from the admin panel.
       * this menu will be used as drop-down or to link any outside url to this system.
       */
      if ($data->href == '') {
        $link_href = '#';
      } else {
        $link_href = $data->href;
      }
    } else {
      // this menu is for the custom page which has been created from the admin panel.
      $link_href = route('dynamic_page', ['slug' => $data->type]);
    }

    return $link_href;
  }
}

if (!function_exists('createInputName')) {
  function createInputName($string)
  {
    $inputName = preg_replace('/\s+/u', '_', trim($string));

    return mb_strtolower($inputName);
  }
}
/// decimal number to integer
if (!function_exists('formatPrice')) {
  function formatPrice($price)
  {
    $priceArray = explode('.', $price);
    $re_number2 = null;
    if (isset($priceArray[1])) {
      $number_2 = ($priceArray[1]);
      if ($number_2 == "00") {
        $re_number2 = null;
      } else {
        $re_number2 = $number_2;
      }
    }
    if (is_null($re_number2)) {
      return $priceArray[0];
    }
    return $priceArray[0] . '.' . $re_number2;
  }
}


if (!function_exists('format_price')) {
  function format_price($value): string
  {
    $bs = Basic::select('base_currency_symbol_position', 'base_currency_symbol')->first();
    if ($bs->base_currency_symbol_position == 'left') {
      return $bs->base_currency_symbol . $value;
    } else {
      return $value . $bs->base_currency_symbol;
    }
  }
}
if (!function_exists('sellerPermission')) {
  function sellerPermission($seller_id, $type, $language_id = null)
  {
    $seller_id = $seller_id;
    $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
    if ($currentPackage) {

      return ['status' => 'true'];
    } else {
      return ['status' => 'false'];
    }
  }
}

if (!function_exists('storeTransaction')) {
  function storeTransaction($data)
  {
    // Get the total profit from the Basic settings table
    $info = App\Models\BasicSettings\Basic::select('total_profit')->first();

    $pre_balance = $info ? $info->total_profit : 0.00;

    if ($data['seller_id'] != null) {
      $after_balance = $pre_balance + $data['sub_total'];
    } else {
      $after_balance = $pre_balance + $data['sub_total'] + $data['tax'];
    }

    // Use updateOrCreate to update or create the transaction
    Transaction::updateOrCreate(
      [
        // Unique identifier for finding the transaction
        'booking_number' => $data['booking_number'] ?? $data['order_number'],
        'transcation_type' => $data['transaction_type'],
      ],
      [
        // Data to update or create
        'transcation_id' => uniqid(),
        'user_id' => $data['user_id'],
        'seller_id' => $data['seller_id'],
        'payment_status' => $data['payment_status'],
        'payment_method' => $data['payment_method'],
        'grand_total' => $data['grand_total'],
        'tax' => $data['tax'],
        'pre_balance' => $pre_balance,
        'after_balance' => $after_balance,
        'gateway_type' => $data['gateway_type'],
        'currency_symbol' => $data['currency_symbol'],
        'currency_symbol_position' => $data['currency_symbol_position'],
      ]
    );
  }
}

if (!function_exists('storeEarnings')) {
  function storeEarnings($data)
  {

    // Fetch the current lifetime earnings and total profit from the database
    $info = DB::table('basic_settings')->select('life_time_earning', 'total_profit')->first();

    // If no record exists, initialize with default values
    if ($info) {
      $lifetimeEarning = $info->life_time_earning + ($data['life_time_earning'] ?? 0);
      $totalProfit     = $info->total_profit + ($data['total_profit'] ?? 0);
    } else {
      $lifetimeEarning = $data['life_time_earning'] ?? 0;
      $totalProfit     = $data['total_profit'] ?? 0;
    }

    // Ensure values do not go below 0
    $lifetimeEarning = max($lifetimeEarning, 0);
    $totalProfit = max($totalProfit, 0);

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'life_time_earning' => $lifetimeEarning,
        'total_profit' => $totalProfit,
      ]
    );
  }
}

if (!function_exists('subtractEarnings')) {
  function subtractEarnings($data)
  {
    // Fetch the current lifetime earnings and total profit from the database
    $info = DB::table('basic_settings')->select('life_time_earning', 'total_profit')->first();

    // Initialize if no record exists
    if (!$info) {
      $lifetimeEarning = 0;
      $totalProfit = 0;
    } else {
      // Subtract the values for rejected or reversed status
      $lifetimeEarning = ($info->life_time_earning ?? 0) - ($data['life_time_earning'] ?? 0);
      $totalProfit = ($info->total_profit ?? 0) - ($data['total_profit'] ?? 0);
    }

    // Ensure values do not go below 0
    $lifetimeEarning = max($lifetimeEarning, 0);
    $totalProfit = max($totalProfit, 0);

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'life_time_earning' => $lifetimeEarning,
        'total_profit' => $totalProfit,
      ]
    );
  }
}

if (!function_exists('storeAmountToSeller')) {
  function storeAmountToSeller($data)
  {

    $seller = Seller::where('id', $data['seller_id'])->first();

    $amount = $seller->amount ?? 0;
    $totalAmount = $amount + $data['sub_total'];
    if ($seller) {
      $seller->amount = $totalAmount;
      $seller->save();
      return $totalAmount;
    } else {
      return;
    }
  }
}

if (!function_exists('subtractAmountFromSeller')) {
  function subtractAmountFromSeller($data)
  {
    $seller = Seller::where('id', $data['seller_id'])->first();

    if (!$seller) {
      return;
    }

    $amount = $seller->amount ?? 0;
    $totalAmount = $amount - $data['sub_total'];

    // Prevent negative amounts, if needed
    $seller->amount = max(0, $totalAmount);
    $seller->save();

    return $seller->amount;
  }
}

if (!function_exists('make_input_name')) {
  function make_input_name($string)
  {
    return preg_replace('/\s+/u', '_', trim($string));
  }
}

if (!function_exists('symbolPrice')) {
  function symbolPrice($price)
  {
    $basic = Basic::where('uniqid', 12345)->select('base_currency_symbol_position', 'base_currency_symbol')->first();
    if ($basic->base_currency_symbol_position == 'left') {
      $data = $basic->base_currency_symbol . number_format($price, 2);
      return str_replace(' ', '', $data);
    } elseif ($basic->base_currency_symbol_position == 'right') {
      $data = number_format($price, 2) . $basic->base_currency_symbol;
      return str_replace(' ', '', $data);
    }
  }
}

if (!function_exists('SellerRatingCount')) {
  function SellerRatingCount($seller_id)
  {

    $spaces = Space::where('seller_id', $seller_id)->select('id')->get();
    $spaceIds = [];
    foreach ($spaces as $space) {
      if (!in_array($space->id, $spaceIds)) {
        array_push($spaceIds, $space->id);
      }
    }
    $data = \App\Models\SpaceReview::whereIn('space_id', $spaceIds)->count('space_id');
    return number_format($data);
  }
}

if (!function_exists('SellerAvgRating')) {
  function SellerAvgRating($seller_id)
  {

    $spaces = Space::where('seller_id', $seller_id)->select('id')->get();
    $spaceIds = [];
    foreach ($spaces as $space) {
      if (!in_array($space->id, $spaceIds)) {
        array_push($spaceIds, $space->id);
      }
    }
    $data = \App\Models\SpaceReview::whereIn('space_id', $spaceIds)->avg('rating');
    return number_format($data, 1);
  }
}
if (!function_exists('hexToRgb')) {
  function hexToRgb($hex)
  {
    // Remove '#' if present
    $hex = str_replace('#', '', $hex);

    // Make sure it's a valid hex color
    if (ctype_xdigit($hex) && (strlen($hex) == 6 || strlen($hex) == 3)) {
      // If it's a shorthand hex color, expand it
      if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
      } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
      }

      return "$r, $g, $b";
    } else {
      return "Invalid Hex Color";
    }
  }
}
//this two function for product onlyDigitalItemsInCart and onlyDigitalItems
if (!function_exists('onlyDigitalItemsInCart')) {
  function onlyDigitalItemsInCart()
  {
    $cart = session()->get('productCart');

    if (!empty($cart)) {
      foreach ($cart as $key => $cartItem) {
        if ($cartItem['type'] != 'digital') {
          return false;
        }
      }
    }
    return true;
  }
}

if (!function_exists('onlyDigitalItems')) {
  function onlyDigitalItems($order)
  {

    $oitems = $order->orderitems;
    foreach ($oitems as $key => $oitem) {

      if ($oitem->item->type != 'digital') {
        return false;
      }
    }

    return true;
  }
}

if (!function_exists('paytabInfo')) {
  function paytabInfo()
  {
    // Could please connect me with a support.who can tell me about live api and test api's Payment url ? Now, I am using this https://secure-global.paytabs.com/payment/request url for testing puporse. Is it work for my live api ???
    // paytabs informations
    $paytabs = OnlineGateway::where('keyword', 'paytabs')->first();
    $paytabsInfo = json_decode($paytabs->information, true);
    if ($paytabsInfo['country'] == 'global') {
      $currency = 'USD';
    } elseif ($paytabsInfo['country'] == 'sa') {
      $currency = 'SAR';
    } elseif ($paytabsInfo['country'] == 'uae') {
      $currency = 'AED';
    } elseif ($paytabsInfo['country'] == 'egypt') {
      $currency = 'EGP';
    } elseif ($paytabsInfo['country'] == 'oman') {
      $currency = 'OMR';
    } elseif ($paytabsInfo['country'] == 'jordan') {
      $currency = 'JOD';
    } elseif ($paytabsInfo['country'] == 'iraq') {
      $currency = 'IQD';
    } else {
      $currency = 'USD';
    }
    return [
      'server_key' => $paytabsInfo['server_key'],
      'profile_id' => $paytabsInfo['profile_id'],
      'url'        => $paytabsInfo['api_endpoint'],
      'currency'   => $currency,
    ];
  }
}

if (!function_exists('getAdminLanguage')) {
  function getAdminLanguage()
  {

    if (Session::has('admin_lang')) {
      $admin_lang = Session::get('admin_lang');
      $cd = str_replace('admin_', '', $admin_lang);
      return Language::where('code', $cd)->first();
    } else {
      return Language::where('is_default', 1)->first();
    }
  }
}

if (!function_exists('getVendorLanguage')) {
  function getVendorLanguage()
  {
    if (Session::has('vendor_lang')) {
      $vendor_lang = Session::get('vendor_lang');
      $cd = str_replace('admin_', '', $vendor_lang);
      return Language::where('code', $cd)->first();
    } else {
      return Language::where('is_default', 1)->first();
    }
  }
}

//To apply the punctuation conditionally
if (!function_exists('formatPunctuation')) {
  function formatPunctuation($text, $direction = 0, $addQuestionMark = false)
  {
    $questionMark = ($direction === 1) ? '&#x061F;' : '?';
    $comma = ($direction === 1) ? '&#x060C;' : ',';

    // Conditionally append the question mark or comma
    if ($addQuestionMark) {
      return $text . $questionMark; // Append question mark only
    } else {
      return $text . $comma; // Append comma only
    }
  }
}

// Text is properly truncated after 2 lines by adding ellipsis
if (!function_exists('truncate_text_space_title')) {
  function truncate_text_space_title($text, $max_length = 90, $ellipsis = '...')
  {
    $text = $text ?? '';
    if (mb_strlen($text, 'UTF-8') > $max_length) {
      return mb_substr($text, 0, $max_length, 'UTF-8') . $ellipsis;
    }
    return $text;
  }
}
if (!function_exists('truncate_text')) {
  function truncate_text($text, $max_length = 90, $ellipsis = '...')
  {
    $text = $text ?? '';
    $text_length = mb_strlen($text, 'UTF-8');

    if ($text_length <= $max_length) {
      return $text;
    }

    // Calculate position for line break (half of max length)
    $break_point = floor($max_length / 2);

    // Get first line (first half)
    $first_line = mb_substr($text, 0, $break_point, 'UTF-8');

    // Get second line (second half)
    $second_line = mb_substr($text, $break_point, $break_point, 'UTF-8');

    // Check if there's more text remaining
    $remaining = $text_length - ($break_point * 2);

    // Add ellipsis to second line if there's remaining text
    $second_line = $remaining > 0 ? $second_line . $ellipsis : $second_line;

    // Combine with line break
    return $first_line . "<br>" . $second_line;
  }

  //amount Abbreviates only millions, billions, and trillions
  if (!function_exists('formatLargeNumber')) {
    function formatLargeNumber($number)
    {
      if ($number >= 1e12) {
        return number_format($number / 1e12, 2) . 'T';
      } elseif ($number >= 1e9) {
        return number_format($number / 1e9, 2) . 'B';
      } elseif ($number >= 1e6) {
        return number_format($number / 1e6, 2) . 'M';
      }
      return number_format($number, 2);
    }
  }

  // this function is used to display the currency symbol postion and format the number
  if (!function_exists('displayCurrency')) {
    function displayCurrency($amount, $settings)
    {
      $symbol = $settings->base_currency_symbol ?? '';
      $position = $settings->base_currency_symbol_position ?? 'left';

      return $position === 'left'
        ? $symbol . formatLargeNumber($amount)
        : formatLargeNumber($amount) . $symbol;
    }
  }

  // The function works based on roles and permissions. To display all the accessible tabs, it checks for the parent tab.
  if (!function_exists('has_permission_group')) {
    function has_permission_group($groupKey, $rolePermissions, $roleInfo = null)
    {
      $config = config("admin_sidebar_permissions.$groupKey");

      if (is_null($roleInfo)) return true;

      $allPermissions = array_merge(
        [$config['parent']],
        $config['children']
      ) ?? [];
      return !empty($rolePermissions) && count(array_intersect($allPermissions, $rolePermissions)) > 0;
    }
  }

  // The function works based on roles and permissions. To display all the accessible tabs, it checks for the children tab.
  if (!function_exists('has_child_permission_only')) {
    function has_child_permission_only(string $permissionName, array $rolePermissions, $roleInfo = null): bool
    {

      if (is_null($roleInfo)) return true;

      if (in_array($permissionName, $rolePermissions)) {
        return true;
      }

      $permissionGroups = config("admin_sidebar_permissions", []);

      foreach ($permissionGroups as $groupKey => $groupConfig) {
        $children = $groupConfig['children'] ?? [];

        if (in_array($permissionName, $children) && in_array($groupConfig['parent'], $rolePermissions)) {
          return true;
        }
      }

      return false;
    }
  }

  // This function checks the current route to highlight the admin navbar's active tab, expand & keep the nav-collapse open or close  start
  if (!function_exists('route_group_is_active')) {
    function route_group_is_active($groupName)
    {
      $routeGroup = config("admin_route_structure.{$groupName}");

      if (!$routeGroup) {
        return false;
      }

      $currentRoute = request()->route()->getName();
      $currentQuery = request()->query();

      // Check single route without loop be carefull , here route means single route as string
      if (isset($routeGroup['route'])) {
        if (check_route_match($routeGroup['route'], $currentRoute, $currentQuery)) {
          return true;
        }
      }

      // Check multiple routes , here routes means multiple route as routes array
      if (isset($routeGroup['routes'])) {
        foreach ($routeGroup['routes'] as $route) {
          if (check_route_match($route, $currentRoute, $currentQuery)) {
            return true;
          }
        }
      }

      return false;
    }
  }

  if (!function_exists('check_route_match')) {
    function check_route_match($route, $currentRoute, $currentQuery)
    {
      // Handle query parameters
      if (str_contains($route, '?')) {
        $routeParts = explode('?', $route);
        $routeName = $routeParts[0];
        parse_str($routeParts[1], $routeQuery);

        return $currentRoute === $routeName &&
          array_intersect_assoc($routeQuery, $currentQuery) == $routeQuery;
      }

      // Exact match
      if ($currentRoute === $route) {
        return true;
      }

      // Wildcard match
      if (str_contains($route, '*')) {
        $pattern = str_replace('\*', '.*', preg_quote($route, '/'));
        return preg_match('/^' . $pattern . '$/', $currentRoute);
      }

      return false;
    }
  }

  // This function checks the current route to highlight the admin navbar's active tab, expand & keep the nav-collapse open or close end
}


if (!function_exists('getPaymentType')) {
  function getPaymentType($sellerId, $packageId)
  {
    $hasPendingMemb = SellerPermissionHelper::hasPendingMembership($sellerId);
    $packageCount = Membership::query()
      ->where([
        ['seller_id', $sellerId],
        ['expire_date', '>=', Carbon::now()->toDateString()]
      ])
      ->whereYear('start_date', '<>', '9999')
      ->where('status', '<>', 2)
      ->count();

    $current_membership = Membership::query()
      ->where([
        ['seller_id', $sellerId],
        ['start_date', '<=', Carbon::now()->toDateString()],
        ['expire_date', '>=', Carbon::now()->toDateString()]
      ])
      ->where('status', 1)
      ->whereYear('start_date', '<>', '9999')
      ->first();

    $current_package = $current_membership ? Package::query()->where('id', $current_membership->package_id)->first() : null;

    if ($packageCount < 2 && !$hasPendingMemb) {

      if (isset($current_package->id) && $current_package->id == $packageId) {
        return 'extend';
      } else {
        return 'membership';
      }
    }

    return null;
  }
}

if (!function_exists('formatTo12Hour')) {
  function formatTo12Hour($time)
  {
    // If already in 12-hour format with AM/PM, return as-is
    if (preg_match('/(AM|PM)$/i', $time)) {
      return $time;
    }

    // Otherwise, convert from 24-hour to 12-hour
    return Carbon::parse($time)->format('h:i A');
  }
}

// Format time based on admin's preferred time format (12h or 24h)
if (!function_exists('formatTimeBasedOnAdminPreference')) {

  function formatTimeBasedOnAdminPreference(?string $time, string $adminTimeFormat): ?string
  {
    if (empty($time)) {
      return null;
    }

    try {
      $parsedTime = Carbon::parse($time);

      return $adminTimeFormat === '12h'
        ? $parsedTime->format('h:i A')  
        : $parsedTime->format('H:i');    
    } catch (\Exception $e) {
      return null; 
    }
  }
}
// format currency for space booking invoice
if (!function_exists('format_currency')) {
  function format_currency($amount, $symbol, $position)
  {
    return $position === 'left' ? $symbol . $amount : $amount . ' ' . $symbol;
  }
}
