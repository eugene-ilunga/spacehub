<?php

namespace App\Models;

use App\Http\Helpers\SellerPermissionHelper;
use App\Models\ClientService\ServiceContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
  use HasFactory;
  protected $fillable = [
    'space_category_id',
    'seller_id',
    'space_type',
    'booking_status',
    'book_a_tour',
    'thumbnail_image',
    'slider_images',
    'space_size',
    'max_guest',
    'min_guest',
    'space_rent',
    'is_featured',
    'average_rating',
    'latitude',
    'longitude',
    'space_status',
    'address',
    'prepare_time',
    'rent_per_hour',
    'similar_space_quantity',
    'opening_time',
    'closing_time',
    'price_per_day',
    'use_slot_rent'
  ];

  public function spaceContents()
  {
    return $this->hasMany(SpaceContent::class, 'space_id', 'id');
  }
  public function booking()
  {
    return $this->hasMany(SpaceBooking::class, 'space_id', 'id');
  }
  public function FeatureBooking()
  {
    return $this->hasMany(SpaceFeature::class, 'space_id', 'id');
  }
  public function spaceService()
  {
    return $this->hasMany(SpaceService::class, 'space_id', 'id');
  }
  public function review()
  {
    return $this->hasMany(SpaceReview::class, 'space_id', 'id');
  }
  public function wishlist()
  {
    return $this->hasMany(SpaceWishlist::class, 'space_id', 'id');
  }
  public function seller()
  {
    return $this->belongsTo(Seller::class, 'seller_id', 'id');
  }
  public function timeSlotRents()
  {
    return $this->hasMany(TimeSlot::class, 'space_id');
  }
  public function timeSlots()
  {
    return $this->hasMany(TimeSlot::class, 'space_id', 'id');
  }

  public static function checkSpaceType($request, $sellerId, $setting){
    
    $matched   = false;
    $spaceType = null;

    if (isset($setting) && !empty($request->type)) {
      if ($sellerId != 0) {
        $hasMembership = SellerPermissionHelper::currentPackagePermission($sellerId);
        if (isset($hasMembership) && !is_null($hasMembership)) {
          $features = json_decode($hasMembership->package_feature, true);

          if (isset($features) && !empty($features)) {
            foreach ($features as $feature) {
              if ($setting->hourly_rental == 1 && $feature == 'Hourly Rental' && $request->type == 'hourly_rental') {
                $matched   = true;
                $spaceType = 'hourly_rental';
                break;
              } elseif ($setting->fixed_time_slot_rental == 1 && $feature == 'Fixed Timeslot Rental' && $request->type == 'fixed_time_slot_rental') {
                $spaceType = 'fixed_time_slot_rental';
                $matched   = true;
                break;
              } elseif ($setting->multi_day_rental == 1 && $feature == 'Multi Day Rental' && $request->type == 'multi_day_rental') {
                $spaceType = 'multi_day_rental';
                $matched   = true;
                break;
              }
            }
          }
        }
      } else {

        if ($setting->hourly_rental == 1 && $request->type == 'hourly_rental') {
          $spaceType = 'hourly_rental';
          $matched   = true;
        } elseif ($setting->fixed_time_slot_rental == 1 && $request->type == 'fixed_time_slot_rental') {
          $spaceType = 'fixed_time_slot_rental';
          $matched   = true;
        } elseif ($setting->multi_day_rental == 1 && $request->type == 'multi_day_rental') {
          $spaceType = 'multi_day_rental';
          $matched   = true;
        }
      }
    }

    return [
      'matched'    => $matched,
      'space_type' => $spaceType
    ];
  }

  public static function getSpaceType($spaceFeatures){
    $spaceType = [];
    if (is_array($spaceFeatures)) {
      foreach ($spaceFeatures as $spaceFeature) {
        if ($spaceFeature == "Fixed Timeslot Rental") {
          $spaceType[] = 1;
        } elseif ($spaceFeature == "Multi Day Rental") {
          $spaceType[] = 3;
        } elseif ($spaceFeature == "Hourly Rental") {
          $spaceType[] = 2;
        }
      }
    }

    return $spaceType;

  }

  
}
