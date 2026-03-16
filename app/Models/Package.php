<?php

namespace App\Models;

use App\Http\Helpers\SellerPermissionHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Package extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'price',
    'term',
    'is_trial',
    'trial_days',
    'status',
    'number_of_service_add',
    'number_of_service_featured',
    'number_of_form_add',
    'number_of_service_order',
    'package_feature',
    'number_of_space',
    'number_of_service_per_space',
    'number_of_option_per_service',
    'number_of_slider_image_per_space',
    'number_of_amenities_per_space',
    'icon',
    'live_chat_status',
    'qr_builder_status',
    'qr_code_save_limit',
    'custom_features',
    'recommended'
  ];

  public function memberships()
  {
    return $this->hasMany(Membership::class);
  }

  // This function retrieves all space IDs based on seller permissions and according to package feature
  public static function getSpaceIdsBySeller($seller_id)
  {
    $spaceIds = [];
    $spaceFeatures = [];

    if ($seller_id != 0) {
      $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
      if ($currentPackage) {
        $spaceFeatures = json_decode($currentPackage->package_feature);
      }
      $spaceType = Space::getSpaceType($spaceFeatures);

      // Fetch spaces for the current seller
      $spaces = Space::select('id', 'space_type', 'seller_id')
        ->where([
          ['seller_id', $seller_id],
          ['space_status', 1],
        ])
        ->whereIn('space_type', $spaceType)
        ->get();

      foreach ($spaces as $space) {
        if ($space->space_type !== null) {
          $spaceIds[] = $space->id;
        }
      }
    } else {
      $defaultSpaces = Space::select('id', 'space_type', 'seller_id')
        ->where('seller_id', 0)
        ->get();

      foreach ($defaultSpaces as $space) {
        // Only add to $spaceIds if space type is not null
        if ($space->space_type !== null) {
          $spaceIds[] = $space->id;
        }
      }
    }

    return $spaceIds;
  }
  // This function retrieves all space IDs based on seller permissions and according to package feature
  public static function getFeaturedSpaceIdsBySeller($seller_id)
  {
    $spaceIds = [];
    $spaceFeatures = [];

    if ($seller_id != 0) {
      $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
      if ($currentPackage) {
        $spaceFeatures = json_decode($currentPackage->package_feature);
      }
      $spaceType = Space::getSpaceType($spaceFeatures);

      // Fetch spaces for the current seller
      $spaces = Space::select('spaces.id', 'spaces.space_type', 'spaces.seller_id')
        ->join('space_features', function ($join) {
          $join->on('spaces.id', '=', 'space_features.space_id')
            ->where(function ($query) {
              $query->where('space_features.end_date', '>', Carbon::now())
                ->orWhereNull('space_features.end_date');
            })
            ->where('space_features.booking_status', '=', 'approved');
        })
      ->where([
        ['spaces.seller_id', $seller_id],
        ['spaces.space_status', 1],
      ])
        
        ->whereIn('spaces.space_type', $spaceType)
        ->get();

      foreach ($spaces as $space) {
        if ($space->space_type !== null) {
          $spaceIds[] = $space->id;
        }
      }
    } else {
      $defaultSpaces = Space::select('spaces.id', 'spaces.space_type', 'spaces.seller_id')
      ->where('spaces.seller_id', 0)
        ->join('space_features', function ($join) {
          $join->on('spaces.id', '=', 'space_features.space_id')
            ->where(function ($query) {
              $query->where('space_features.end_date', '>', Carbon::now())
                ->orWhereNull('space_features.end_date');
            })
            ->where('space_features.booking_status', '=', 'approved');
        })
        ->get();

      foreach ($defaultSpaces as $space) {
        // Only add to $spaceIds if space type is not null
        if ($space->space_type !== null) {
          $spaceIds[] = $space->id;
        }
      }
    }
   

    return $spaceIds;
  }
}
