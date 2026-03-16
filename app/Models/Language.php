<?php

namespace App\Models;

use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\PostInformation;
use App\Models\CustomPage\PageContent;
use App\Models\FAQ;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\AboutSection;
use App\Models\HomePage\HeroSlider;
use App\Models\HomePage\HeroStatic;
use App\Models\HomePage\SectionTitle;
use App\Models\HomePage\Testimonial;
use App\Models\HomePage\WorkProcessSection;
use App\Models\MenuBuilder;
use App\Models\Popup;
use App\Models\Shop\ProductCategory;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ShippingCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  use HasFactory;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'code', 'direction', 'is_default'];


    public function pageName()
  {
    return $this->hasOne(PageHeading::class);
  }

  public function seoInfo()
  {
    return $this->hasOne(SEO::class);
  }

  public function cookieAlertInfo()
  {
    return $this->hasOne(CookieAlert::class);
  }

  public function faq()
  {
    return $this->hasMany(FAQ::class);
  }

  public function customPageInfo()
  {
    return $this->hasMany(PageContent::class);
  }

  public function footerContent()
  {
    return $this->hasOne(FooterContent::class);
  }

  public function footerQuickLink()
  {
    return $this->hasMany(QuickLink::class);
  }
  public function featureCharge()
  {
    return $this->hasMany(FeatureCharge::class, 'language_id', 'id');
  }

  public function announcementPopup()
  {
    return $this->hasMany(Popup::class);
  }

  public function blogCategory()
  {
    return $this->hasMany(BlogCategory::class);
  }

  public function postInformation()
  {
    return $this->hasMany(PostInformation::class, 'language_id', 'id');
  }

  public function menuInfo()
  {
    return $this->hasOne(MenuBuilder::class, 'language_id', 'id');
  }

  public function testimonial()
  {
    return $this->hasMany(Testimonial::class, 'language_id', 'id');
  }
  public function country()
  {
    return $this->hasMany(Country::class, 'language_id', 'id');
  }
  public function state()
  {
    return $this->hasMany(State::class, 'language_id', 'id');
  }
  public function spaceContent()
  {
    return $this->hasMany(SpaceContent::class, 'language_id', 'id');
  }
  public function shippingCharge()

  {
    return $this->hasMany(ShippingCharge::class);
  }
  public function productCategory()
  {
    return $this->hasMany(ProductCategory::class);
  }

  public function productContent()
  {
    return $this->hasMany(ProductContent::class);
  }
  public function spaceServiceContent()
  {
    return $this->hasMany(SpaceServiceContent::class, 'language_id', 'id');
  }
  public function subServiceContent()
  {
    return $this->hasMany(SubServiceContent::class, 'language_id', 'id');
  }
  public function spaceCategory()
  {
    return $this->hasMany(SpaceCategory::class, 'language_id', 'id');
  }
  public function spaceSubcategory()
  {
    return $this->hasMany(SpaceSubCategory::class, 'language_id', 'id');
  }

  public function heroSlider()
  {
    return $this->hasMany(HeroSlider::class, 'language_id', 'id');
  }

  public function sectionTitle()
  {
    return $this->hasOne(SectionTitle::class, 'language_id', 'id');
  }

  public function aboutSection()
  {
    return $this->hasOne(AboutSection::class, 'language_id', 'id');
  }

  public function workProcessSection()
  {
    return $this->hasMany(WorkProcessSection::class, 'language_id', 'id');
  }


  public function heroStatic()
  {
    return $this->hasOne(HeroStatic::class, 'language_id', 'id');
  }

  public function aboutContent()
  {
    return $this->hasOne(AboutContent::class, 'language_id', 'id');
  }
  public function contactContent()
  {
    return $this->hasOne(ContactContent::class, 'language_id', 'id');
  }
  public function form()
  {
    return $this->hasMany(Form::class, 'language_id', 'id');
  }

  public static function dashboardAttribute()
  {


    // Update existing keys
    $newKeys = [
      'direction' => 'direction',
      'space_type' => 'space_type',
      'cookie_alert_status' => 'cookie_alert_status',
      'cookie_alert_btn_text' => 'cookie_alert_btn_text',
      'keyword' => 'keyword',
      'cookie_alert_text' => 'cookie_alert_text',
      'name' => 'name',
      'username' => 'username',
      'email' => 'email address',
      'first_name' => 'first name',
      'last_name' => 'last name',
      'password' => 'password',
      'password_confirmation' => 'confirm password',
      'city' => 'city',
      'country' => 'country',
      'address' => 'address',
      'phone' => 'phone',
      'mobile' => 'mobile',
      'age' => 'age',
      'sex' => 'sex',
      'gender' => 'gender',
      'day' => 'day',
      'month' => 'month',
      'year' => 'year',
      'hour' => 'hour',
      'minute' => 'minute',
      'second' => 'second',
      'title' => 'title',
      'subtitle' => 'subtitle',
      'text' => 'text',
      'description' => 'description',
      'content' => 'content',
      'occupation' => 'occupation',
      'comment' => 'comment',
      'rating' => 'rating',
      'terms' => 'terms',
      'question' => 'question',
      'answer' => 'answer',
      'status' => 'status',
      'term' => 'term',
      'price' => 'price',
      'amount' => 'amount',
      'date' => 'date',
      'latitude' => 'latitude',
      'longitude' => 'longitude',
      'value' => 'value',
      'type' => 'type',
      'code' => 'code',
      'url' => 'url',
      'stock' => 'stock',
      'delay' => 'delay',
      'image' => 'image',
      'language_id' => 'language',
      'serial_number' => 'serial number',
      'use_slot_rent' => 'use slot rent',
      'category_id' => 'category',
      'start_time' => 'start time',
      'end_time' => 'end time',
      'start_date' => 'start date',
      'short_text' => 'short text',
      'email_address' => 'email address',
      'contact_number' => 'contact number',
      'new_password' => 'new password',
      'new_password_confirmation' => 'new password confirmation',
      'google_adsense_publisher_id' => 'google adsense publisher id',
      'ad_type' => 'ad type',
      'resolution_type' => 'resolution type',
      'button_text' => 'button text',
      'button_url' => 'button url',
      'background_color_opacity' => 'background color opacity',
      'base_currency_symbol' => 'base currency symbol',
      'base_currency_symbol_position' => 'base currency symbol position',
      'base_currency_text' => 'base currency text',
      'base_currency_text_position' => 'base currency text position',
      'base_currency_rate' => 'base currency rate',
      'website_title' => 'website title',
      'secondary_color' => 'secondary color',
      'primary_color' => 'primary color',
      'preloader' => 'preloader',
      'preloader_status' => 'preloader status',
      'logo' => 'logo',
      'favicon' => 'favicon',
      'smtp_host' => 'smtp host',
      'smtp_port' => 'smtp port',
      'encryption' => 'encryption',
      'from_name' => 'from name',
      'from_mail' => 'from mail',
      'smtp_password' => 'smtp password',
      'smtp_username' => 'smtp username',
      'mail_subject' => 'mail subject',
      'mail_body' => 'mail body',
      'cookie_alert_text' => 'cookie alert text',
      'role_id' => 'role_id',
      'paypal_status' => 'paypal status',
      'paypal_sandbox_status' => 'paypal sandbox status',
      'paypal_client_id' => 'paypal client ID',
      'paypal_client_secret' => 'paypal client secret',
      'instamojo_status' => 'instamojo status',
      'instamojo_sandbox_status' => 'instamojo sandbox status',
      'instamojo_key' => 'instamojo API key',
      'instamojo_token' => 'instamojo auth token',
      'paytm_status' => 'paytm status',
      'paytm_environment' => 'paytm environment',
      'paytm_merchant_key' => 'paytm merchant key',
      'paytm_merchant_mid' => 'paytm merchant MID',
      'paytm_merchant_website' => 'paytm merchant website',
      'paytm_industry_type' => 'paytm industry type',
      'stripe_status' => 'stripe status',
      'stripe_key' => 'stripe key',
      'stripe_secret' => 'stripe secret',
      'flutterwave_status' => 'flutterwave status',
      'flutterwave_public_key' => 'flutterwave public key',
      'flutterwave_secret_key' => 'flutterwave secret key',
      'razorpay_status' => 'razorpay status',
      'razorpay_key' => 'razorpay key',
      'razorpay_secret' => 'razorpay secret',
      'mollie_status' => 'mollie status',
      'mollie_key' => 'mollie API key',
      'paystack_status' => 'paystack status',
      'paystack_key' => 'paystack API key',
      'mercadopago_status' => 'mercadopago status',
      'mercadopago_sandbox_status' => 'mercadopago sandbox status',
      'mercadopago_token' => 'mercadopago token',
      'authorize_net_status' => 'Authorize.Net status',
      'sandbox_check' => 'sandbox check',
      'login_id' => 'login ID',
      'transaction_key' => 'transaction key',
      'public_key' => 'public key',
      'google_map_api_key' => 'google map api key',
      'google_map_radius' => 'google map radius',
      'disqus_short_name' => 'disqus short name',
      'tawkto_status' => 'tawkto status',
      'tawkto_direct_chat_link' => 'tawkto direct chat link',
      'whatsapp_number' => 'whatsapp number',
      'whatsapp_header_title' => 'whatsapp header title',
      'whatsapp_popup_message' => 'whatsapp popup message',
      'google_recaptcha_site_key' => 'google recapta site key',
      'google_recaptcha_status' => 'google recapta status',
      'google_recaptcha_secret_key' => 'google recapta secret key',
      'google_client_id' => 'google client id',
      'google_client_secret' => 'google client secret',
      'google_login_status' => 'google login status',
      'current_password' => 'current password',
      'expiration_reminder' => 'expiration reminder',
      'icon' => 'icon',
      'number_of_space' => 'number_of_space',
      'number_of_service_per_space' => 'number_of_service_per_space',
      'number_of_option_per_service' => 'number_of_option_per_service',
      'number_of_slider_image_per_space' => 'number_of_slider_image_per_space',
      'number_of_amenities_per_space' => 'number_of_amenities_per_space',
      'number_of_amenities_per_space' => 'number_of_amenities_per_space',
      'tax' => 'tax',
      'space_units' => 'space_units',
      'fixed_time_slot_rental' => 'fixed_time_slot_rental',
      'hourly_rental' => 'hourly_rental',
      'multi_day_rental' => 'multi_day_rental',
      'space_id' => 'space_id',
      'seller_id' => 'seller_id',
      'space_type' => 'space_type',
      'coupon_type' => 'coupon_type',
      'end_date' => 'end_date',
      'spaces' => 'spaces',
      'state_id' => 'state_id',
      'country_id' => 'country_id',
      'icon_image' => 'icon_image',
      'category_description' => 'category_description',
      'bg_image' => 'bg_image',
      'space_category_id' => 'space_category_id',
      'thumbnail_image' => 'thumbnail image',
      'latitude' => 'latitude',
      'longitude' => 'longitude',
      'min_guest' => 'minimum_guest',
      'max_guest' => 'maximum guest',
      'space_size' => 'space size',
      'space_rent' => 'space rent',
      'booking_status' => 'booking status',
      'book_a_tour' => 'book a tour',
      'prepare_time' => 'prepare time',
      'rent_per_hour' => 'rent per hour',
      'opening_time' => 'opening time',
      'closing_time' => 'closing time',
      'price_per_day' => 'price per day',
      'has_sub_services' => 'has sub services',
      'price_type' => 'price type',
      'is_custom_day' => 'is custom day',
      'subservice_selection_type' => 'subservice selection type',
      'sub_service_status' => 'sub service status',
      'number_of_booking' => 'number of booking',
      'number_of_day' => 'number of day',
      'charge_price' => 'charge price',
      'space' => 'space',
      'bookingDate' => 'booking date',
      'numberOfGuest' => 'number of guest',
      'fullName' => 'full name',
      'customerPhoneNumber' => 'customer phone number',
      'customerEmailAddress' => 'customer email address',
      'paymentStatus' => 'payment status',
      'timeSlotId' => 'time slot id',
      'startTime' => 'start time',
      'shop_status' => 'shop status',
      'short_text' => 'short text',
      'shipping_charge' => 'shipping charge',
      'input_type' => 'input type',
      'file' => 'file',
      'link' => 'link',
      'current_price' => 'current price',
      'featured_image' => 'featured image',
      'slider_images' => 'slider images',
      'min_limit' => 'min limit',
      'max_limit' => 'max limit',
      'password' => 'password',
      'subject' => 'subject',
      'message' => 'message',
      'button_name' => 'button name',
      'phone_number' => 'phone number',
      'recipient_mail' => 'recipient mail',
      'amount_status' => 'amount status',
      'color' => 'color',
      'number' => 'number',
      'testimonial_bg_img' => 'testimonial bg img',
      'about_section_image' => 'about section image',
      'sub_title' => 'sub title',
      'sub_text' => 'sub text',
      'footer_background_color' => 'footer background color',
      'about_company' => 'about company',
      'copyright_text' => 'copy right text',
      'admin_id' => 'admin',
      'slot' => 'slot',
      'background_color' => 'background color',
      'button_color' => 'button color',
      'to_mail' => 'to mail',
      'disqus_status' => 'disqus_status',
      'theme_version' => 'theme_version',
      'breadcrumb_overlay_color' => 'breadcrumb_overlay_color',
      'breadcrumb_overlay_opacity' => 'breadcrumb_overlay_opacity',
      'breadcrumb' => 'breadcrumb',
      'google_map_api_key_status' => 'google_map_api_key_status',
      'smtp_status' => 'smtp_status',
      'whatsapp_status' => 'whatsapp_status',
      'whatsapp_popup_status' => 'whatsapp_popup_status',
      'facebook_login_status' => 'facebook_login_status',
      'facebook_app_id' => 'facebook_app_id',
      'facebook_app_secret' => 'facebook_app_secret',
      'pusher_app_id' => 'pusher_app_id',
      'pusher_key' => 'pusher_key',
      'pusher_secret' => 'pusher_secret',
      'pusher_cluster' => 'pusher_cluster',
      'maintenance_img' => 'maintenance_img',
      'maintenance_status' => 'maintenance_status',
      'maintenance_msg' => 'maintenance_msg',
      'product_tax_amount' => 'product_tax_amount',
      'sandbox_status' => 'sandbox_status',
      'merchant_id' => 'merchant_id',
      'salt_key' => 'salt_key',
      'salt_index' => 'salt_index',
      'secret_key' => 'secret_key',
      'category_code' => 'category_code',
      'authorizenet_status' => 'authorizenet_status',
      'authorizenet_sandbox_status' => 'authorizenet_sandbox_status',
      'authorizenet_api_login_id' => 'authorizenet_api_login_id',
      'authorizenet_transaction_key' => 'authorizenet_transaction_key',
      'authorizenet_public_client_key' => 'authorizenet_public_client_key',
      'token' => 'token',
      'country' => 'country',
      'server_key' => 'server_key',
      'profile_id' => 'profile_id',
      'api_endpoint' => 'api_endpoint',
      'has_attachment' => 'has_attachment',
      'withdraw_method' => 'withdraw_method',
      'withdraw_amount' => 'withdraw_amount',
      'reply' => 'reply',
      'package_id' => 'package_id',
      'feature_charge' => 'feature_charge',
      'perfect_money_wallet_id' => 'perfect_money_wallet_id',
      'is_required' => 'is_required',
      'label' => 'label',
      'placeholder' => 'placeholder',
      'options' => 'options',
      'file_size' => 'file_size',
      'payment_method' => 'payment_method',
      'position' => 'position',
      'page_type' => 'page_type',
      'time_slot_rent' => 'time_slot_rent',
      'similar_space_quantity' => 'similar_space_quantity',
      'newsletter_text' => 'newsletter text',
    ];
    return $newKeys;
  }

  //front attribute
  public static function frontAttribute()
  {
    $newKeys = [
      'name' => 'name',
      'first_name' => 'first name',
      'gateway' => 'gateway',
      'phone' => 'phone',
      'address' => 'address',
      'email' => 'email address',
      'email_id' => 'email address',
      'subject' => 'subject',
      'message' => 'message',
      'username' => 'username',
      'user_name' => 'user_name',
      'password' => 'password',
      'password_confirmation' => 'confirm password',
      'current_password' => 'current password',
      'new_password' => 'new password',
      'new_password_confirmation' => 'new confirm password',
      'billing_name' => 'billing name',
      'billing_email' => 'billing email',
      'billing_phone' => 'billing phone',
      'billing_city' => 'billing city',
      'billing_country' => 'billing country',
      'billing_zip_code' => 'billing zip code',
      'billing_address' => 'billing address',
      'email_address' => 'email address',
      'shipping_name' => 'shipping name',
      'shipping_email' => 'shipping email',
      'shipping_phone' => 'shipping phone',
      'shipping_city' => 'shipping city',
      'shipping_country' => 'shipping country',
      'shipping_zip_code' => 'shipping zip code',
      'shipping_address' => 'shipping address',
      'first_name_for_iyzico' => 'first name for iyzico',
      'last_name_for_iyzico' => 'last name for iyzico',
      'identity_number_for_iyzico' => 'identity number for iyzico',
      'email_address_for_iyzico' => 'email address for iyzico',
      'phone_number_for_iyzico' => 'phone number for iyzico',
      'zip_code_for_iyzico' => 'zip code for iyzico',
      'address_for_iyzico' => 'address for iyzico',
      'country_for_iyzico' => 'country for iyzico',
      'city_for_iyzico' => 'city for iyzico',
      'rating' => 'rating',
      'g-recaptcha-response' => 'g-recaptcha-response',
    ];
    
    return $newKeys;
  }


}
