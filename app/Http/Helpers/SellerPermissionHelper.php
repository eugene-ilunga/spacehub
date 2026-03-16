<?php

namespace App\Http\Helpers;

use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Space;
use App\Models\SpaceService;
use App\Models\SubService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;

class SellerPermissionHelper
{

  public static function packagePermission(int $seller_id)
  {
    $bs = Basic::first();
    Config::set('app.timezone', $bs->timezone);

    $currentPackage = Membership::query()->where([
      ['seller_id', '=', $seller_id],
      ['status', '=', 1],
      ['start_date', '<=', Carbon::now()->format('Y-m-d')],
      ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
    ])->first();
    $package = isset($currentPackage) ? Package::query()->find($currentPackage->package_id) : null;
    return $package ? $package : collect([]);
  }

  public static function uniqidReal($lenght = 13)
  {
    $bs = Basic::first();
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
      $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
      $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
      throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
  }

  public static function currentPackagePermission(int $userId)
  {
    $bs = Basic::first();
    Config::set('app.timezone', $bs->timezone);
    $currentPackage = Membership::query()->where([
      ['seller_id', '=', $userId],
      ['status', '=', 1],
      ['start_date', '<=', Carbon::now()->format('Y-m-d')],
      ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
    ])->first();
    return isset($currentPackage) ? Package::query()->findOrFail($currentPackage->package_id) : null;
  }
  public static function userPackage(int $userId)
  {
    $bs = Basic::first();
    Config::set('app.timezone', $bs->timezone);

    return Membership::query()->where([
      ['seller_id', '=', $userId],
      ['status', '=', 1],
      ['start_date', '<=', Carbon::now()->format('Y-m-d')],
      ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
    ])->first();
  }

  public static function currPackageOrPending($userId)
  {

    $currentPackage = Self::currentPackagePermission($userId);
    if (!$currentPackage) {
      $currentPackage = Membership::query()->where([
        ['seller_id', '=', $userId],
        ['status', 0]
      ])->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
      $currentPackage = isset($currentPackage) ? Package::query()->findOrFail($currentPackage->package_id) : null;
    }
    return isset($currentPackage) ? $currentPackage : null;
  }

  public static function currMembOrPending($userId)
  {
    $currMemb = Self::userPackage($userId);
    if (!$currMemb) {
      $currMemb = Membership::query()->where([
        ['seller_id', '=', $userId],
        ['status', 0],
      ])->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
    }
    return isset($currMemb) ? $currMemb : null;
  }


  public static function hasPendingMembership($userId)
  {
    $count = Membership::query()->where([
      ['seller_id', '=', $userId],
      ['status', 0]
    ])->whereYear('start_date', '<>', '9999')->count();
    return $count > 0 ? true : false;
  }

  public static function nextPackage(int $userId)
  {
    $bs = Basic::first();
    Config::set('app.timezone', $bs->timezone);
    $currMemb = Membership::query()->where([
      ['seller_id', $userId],
      ['start_date', '<=', Carbon::now()->toDateString()],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999');
    $nextPackage = null;
    if ($currMemb->first()) {
      $countCurrMem = $currMemb->count();
      if ($countCurrMem > 1) {
        $nextMemb = $currMemb->orderBy('id', 'DESC')->first();
      } else {
        $nextMemb = Membership::query()->where([
          ['seller_id', $userId],
          ['start_date', '>', $currMemb->first()->expire_date]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
      }
      $nextPackage = $nextMemb ? Package::query()->where('id', $nextMemb->package_id)->first() : null;
    }
    return $nextPackage;
  }

  public static function nextMembership(int $userId)
  {
    $bs = Basic::first();
    Config::set('app.timezone', $bs->timezone);
    $currMemb = Membership::query()->where([
      ['seller_id', $userId],
      ['start_date', '<=', Carbon::now()->toDateString()],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999');
    $nextMemb = null;
    if ($currMemb->first()) {
      $countCurrMem = $currMemb->count();
      if ($countCurrMem > 1) {
        $nextMemb = $currMemb->orderBy('id', 'DESC')->first();
      } else {
        $nextMemb = Membership::query()->where([
          ['seller_id', $userId],
          ['start_date', '>', $currMemb->first()->expire_date]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
      }
    }
    return $nextMemb;
  }
  public static function getPackageInfoByMembership($membership_id)
  {
    $bs = Basic::first();
    Config::set('app.timezone', $bs->timezone);
    $membership = Membership::query()->where('id', $membership_id)->select('package_id')->first();
    if ($membership) {
      $pacakge = Package::where([['id', $membership->package_id], ['status', 1]])->first();
      if ($pacakge) {
        if ($pacakge->live_chat_status == 1) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public static function getPackageInfo($seller_id, $membership_id)
  {
    $membership = Membership::where([['seller_id', $seller_id], ['id', $membership_id]])->first();
    if (!empty($membership)) {
      $package = Package::where('id', $membership->package_id)->first();
      if (!empty($package)) {
        if ($package->live_chat_status == 1) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public static function spaceCount($seller_id)
  {
    $currentPackage = Self::currentPackagePermission($seller_id);
    $space_type = [];
    // $features = json_decode($currentPackage->package_feature, true);
    $features = json_decode(optional($currentPackage)->package_feature, true) ?? [];
    if (in_array('Fixed Timeslot Rental', $features)) {
      $space_type[] = 1;
    }
    if (in_array('Hourly Rental', $features)) {
      $space_type[] = 2;
    }
    if (in_array('Multi Day Rental', $features)) {
      $space_type[] = 3;
    }
    if ($currentPackage) {
      $total_space = Space::where('seller_id', $seller_id)
        ->whereIn('space_type', $space_type)
        ->count();

      $remaining_space = $currentPackage->number_of_space - $total_space;
      if ($remaining_space < 0) {
        return 'downgraded';
      } else {
        return $remaining_space;
      }
    }
  }

  public static function amenitiesCount($seller_id)
  {
    $currentPackage = Self::currentPackagePermission($seller_id);
    $languages = Language::select('id')->get();
    $space_type = [];
    $features = json_decode(optional($currentPackage)->package_feature, true) ?? [];
    if (in_array('Fixed Timeslot Rental', $features)) {
      $space_type[] = 1;
    }
    if (in_array('Hourly Rental', $features)) {
      $space_type[] = 2;
    }
    if (in_array('Multi Day Rental', $features)) {
      $space_type[] = 3;
    }

    $amenities = [];
    if ($currentPackage) {

      $spaces = Space::where('seller_id', $seller_id)
        ->whereIn('space_type', $space_type)
        ->select('id')
        ->get();
      $spaceContents = [];
      foreach ($spaces as $space) {
        foreach ($languages as $language) {
          $spaceContents = array_merge($spaceContents, $space->spaceContents()->where('language_id', $language->id)->select('space_id', 'amenities', 'title')->get()->toArray());
        }
      }
      foreach ($spaceContents as $item) {
        $amenitiesCount = json_decode($item['amenities'], true) ?? [];
        if (count($amenitiesCount) > $currentPackage->number_of_amenities_per_space) {
          $amenities[] = [
            'space_id' => $item['space_id'],
            'title' => $item['title']
          ];
        }
      }
    }
    return $amenities;
  }

  public static function serviceCount($seller_id)
  {
    $currentPackage = Self::currentPackagePermission($seller_id);
    $space_ids = [];
    $space_type = [];
    $features = json_decode(optional($currentPackage)->package_feature, true) ?? [];
    if (in_array('Fixed Timeslot Rental', $features)) {
      $space_type[] = 1;
    }
    if (in_array('Hourly Rental', $features)) {
      $space_type[] = 2;
    }
    if (in_array('Multi Day Rental', $features)) {
      $space_type[] = 3;
    }

    if ($currentPackage) {
      $spaces = Space::where('seller_id', $seller_id)
        ->whereIn('space_type', $space_type)
        ->select('id')
        ->get();

      foreach ($spaces as $space) {
        $spaceServices = SpaceService::where('space_id', $space->id)->select('id', 'space_id')->get();

        if (count($spaceServices) > $currentPackage->number_of_service_per_space) {
          $uniqueSpaceIds = $spaceServices->unique('space_id')->pluck('space_id')->toArray();

          foreach ($uniqueSpaceIds as $spaceId) {
            $space_ids[] = [
              'space_id' => $spaceId
            ];
          }
        }
      }
    }

    return $space_ids;
  }

  public static function sliderImageCount($seller_id)
  {
    $currentPackage = Self::currentPackagePermission($seller_id);
    $space_ids = [];
    $space_type = [];
    $features = json_decode(optional($currentPackage)->package_feature, true) ?? [];
    if (in_array('Fixed Timeslot Rental', $features)) {
      $space_type[] = 1;
    }
    if (in_array('Hourly Rental', $features)) {
      $space_type[] = 2;
    }
    if (in_array('Multi Day Rental', $features)) {
      $space_type[] = 3;
    }

    if ($currentPackage) {
      $spaces = Space::where('seller_id', $seller_id)
        ->whereIn('space_type', $space_type)
        ->select('id', 'slider_images')
        ->get();

      if ($spaces) {
        foreach ($spaces as $space) {
          $sliderImages = json_decode($space->slider_images, true);

          if (count($sliderImages) > $currentPackage->number_of_slider_image_per_space) {
            $space_ids[] = $space->id;
          }
        }
      }
    }
    return $space_ids;
  }

  public static function optionCount($seller_id)
  {
    $currentPackage = Self::currentPackagePermission($seller_id);
    $space_ids = [];
    $service_ids = [];

    if ($currentPackage) {
      $spaces = Space::where('seller_id', $seller_id)->select('id')->get();

      foreach ($spaces as $space) {
        $spaceServices = SpaceService::where('space_id', $space->id)->select('id')->get();

        foreach ($spaceServices as $service) {
          $subServices = SubService::where('service_id', $service->id)->get();
          if (count($subServices) > $currentPackage->number_of_option_per_service) {
            $uniqueServiceIds = $subServices->unique('service_id')->pluck('service_id')->toArray();
            foreach ($uniqueServiceIds as $id) {
              $service_ids[] = [
                'service_id' => $id
              ];
            }
          }
        }
      }
    }

    return $service_ids;
  }
}
