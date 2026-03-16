<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\BasicSettings\SEO;
use App\Models\BasicSettings\SocialMedia;
use App\Models\Contact;
use App\Models\Footer\FooterContent;
use App\Models\HomePage\Section;
use App\Models\HomePage\SectionContent;
use App\Models\Language;
use App\Models\Timezone;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton('websiteSettings', function () {
      return DB::table('basic_settings')
        ->select([
          'website_title',
          'fixed_time_slot_rental',
          'hourly_rental',
          'multi_day_rental',
          'google_map_api_key',
          'google_map_api_key_status',
          'time_format',
          'email_address',
          'address',
          'contact_number',
          'admin_theme_version',
          'theme_version',
          'base_currency_symbol',
          'base_currency_symbol_position',
          'base_currency_text',
          'base_currency_text_position',
          'base_currency_rate',
          'tax',
          'life_time_earning',
          'total_profit',
          'space_units',
          'logo',
          'favicon',
          'time_zone',
        ])
        ->first();
    });

    // Register the current language as a singleton
    $this->app->singleton('currentLanguage', function () {
      return getAdminLanguage();
    });

    // Register a singleton for footer text
    $this->app->singleton('footerText', function () {
      $language = app('currentLanguage'); // Get the current language
      if ($language && method_exists($language, 'footerContent')) {
        return $language->footerContent()->first();
      }
      return null;
    });
    // Register a singleton for languages
    $this->app->singleton('langs', function () {
      return Language::all(); // Fetch all languages
    });


    // Register the current language as a singleton
    $this->app->singleton('vendorCurrentLanguage', function () {
      return getVendorLanguage();
    });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Paginator::useBootstrap();

    if (!app()->runningInConsole()) {

      // Fetch the timezone from the database or .env
      $time_zone = DB::table('basic_settings')->value('time_zone');
      $timezone = Timezone::where('id', '=', $time_zone)->value('timezone') ?? config('app.timezone');

      // Set the application timezone dynamically
      Config::set('app.timezone', $timezone);
      date_default_timezone_set($timezone);

      $data = DB::table('basic_settings')->select('favicon', 'website_title', 'logo', 'base_currency_text', 'base_currency_text_position', 'maintenance_img', 'maintenance_msg', 'google_map_api_key', 'google_map_api_key_status', 'space_units')->first();

      $websiteSettings = app('websiteSettings');
      $footerText = app('footerText');
      $langs = app('langs');

      // send this information to only back-end view files
      View::composer(['admin.*'], function ($view) use ($websiteSettings, $footerText, $langs) {

        if (Auth::guard('admin')->check() == true) {
          $authAdmin = Auth::guard('admin')->user();
          $role      = null;
          if (!is_null($authAdmin->role_id)) {
            $role = $authAdmin->role()->first();
          }
        }

        // get language form session
        $adminLang = getAdminLanguage();

        app()->setLocale('admin_' . $adminLang->code);

        if (Auth::guard('admin')->check() == true) {
          $view->with('roleInfo', $role);
        }

        $view->with('defaultLang', $adminLang);
        $view->with('settings', $websiteSettings);
        $view->with('footerTextInfo', $footerText);
        $view->with('langs', $langs);
      });

      // send this information to only vendors view files
      View::composer(['vendors.*'], function ($view) {
        $langs = Language::all();
        $language = getVendorLanguage();
        app()->setLocale('admin_' . $language->code);

        $seo = SEO::where('language_id', $language->id)->first();
        $footerText = FooterContent::where('language_id', $language->id)->first();
        $websiteSettings = DB::table('basic_settings')->select('base_currency_symbol', 'fixed_time_slot_rental', 'hourly_rental', 'multi_day_rental', 'google_map_api_key_status', 'google_map_api_key', 'base_currency_symbol_position', 'base_currency_text', 'time_zone', 'base_currency_text_position', 'base_currency_rate', 'time_format', 'space_units', 'tax')->first();

        $view->with('defaultLang', $language);
        $view->with('settings', $websiteSettings);
        $view->with('seo', $seo);
        $view->with('footerTextInfo', $footerText);
        $view->with('langs', $langs);
      });

      // send this information to only front-end view files

      View::composer('frontend.*', function ($view) {
        // Get basic info
        $basicData = DB::table('basic_settings')
          ->select(
            'theme_version',
            'logo',
            'email_address',
            'contact_number',
            'address',
            'primary_color',
            'google_map_api_key',
            'secondary_color',
            'breadcrumb_overlay_color',
            'time_format',
            'time_zone',
            'whatsapp_status',
            'whatsapp_number',
            'whatsapp_header_title',
            'whatsapp_popup_status',
            'whatsapp_popup_message',
            'is_language',
            'breadcrumb_overlay_opacity',
            'base_currency_symbol',
            'base_currency_symbol_position',
            'tax',
            'shop_status',
            'footer_logo',
            'banner_section_bg_img',
            'work_process_background_img',
            'testimonial_bg_img',
            'footer_section_bg_img',
            'preloader',
            'preloader_status',
            'admin_profile',
            'google_map_api_key_status',
            'guest_checkout_status',
            'banner_section_foreground_img',
          )
          ->first();

        // Get all languages of the system
        $allLanguages = Language::all();

        // Get the current locale of this website
        $locale = null;
        if (Session::has('currentLocaleCode')) {
          $locale = Session::get('currentLocaleCode');
        }

        if (empty($locale)) {
          $language = Language::query()->where('is_default', '=', 1)->first();
        } else {
          $language = Language::query()->where('code', '=', $locale)->first();
        }

        // Get the menus of this website with null checks
        if ($language && $language->menuInfo) {
          $siteMenuInfo = $language->menuInfo;
          $menus = $siteMenuInfo ? $siteMenuInfo->menus : json_encode([]);
        } else {
          $menus = json_encode([]);
        }

        // Get the announcement popups
        if ($language && method_exists($language, 'announcementPopup')) {
          $popups = $language->announcementPopup()->where('status', 1)->orderBy('serial_number', 'asc')->get();
        } else {
          $popups = collect();
        }

        // Get the cookie alert info
        $cookieAlert = ($language && method_exists($language, 'cookieAlertInfo'))
          ? $language->cookieAlertInfo()->first()
          : null;

        // Get home section info based on current language
        $homeSectionInfo = $language
          ? SectionContent::where('language_id', $language->id)->first()
          : null;

        // Get the footer info
        $footerData = ($language && method_exists($language, 'footerContent'))
          ? $language->footerContent()->first()
          : null;

        // Get social media infos
        $socialMedias = SocialMedia::query()->orderBy('serial_number', 'asc')->get();

        // Get quick links
        $quickLinks = ($language && method_exists($language, 'footerQuickLink'))
          ? $language->footerQuickLink()->orderBy('serial_number', 'asc')->get()
          : collect();

        // Get active section
        $isActiveSection = Section::query()->first();

        // Contact info with null checks
        $contact = Contact::first();

        $emails = ($contact && $contact->email_address)
          ? explode(',', $contact->email_address)
          : [];

        $mobiles = ($contact && $contact->mobile_number)
          ? explode(',', $contact->mobile_number)
          : [];

        // Get contact location info
        $contactLocation = ($language && method_exists($language, 'contactContent'))
          ? $language->contactContent()->first()
          : null;

        $address = $contactLocation ? $contactLocation->location : '';

        // Share all data with the view
        $view->with([
          'basicInfo'           => $basicData,
          'allLanguageInfos'    => $allLanguages,
          'currentLanguageInfo' => $language,
          'socialMediaInfos'    => $socialMedias,
          'menuInfos'           => $menus,
          'popupInfos'          => $popups,
          'cookieAlertInfo'     => $cookieAlert,
          'footerInfo'          => $footerData,
          'quickLinkInfos'      => $quickLinks,
          'isActiveSection'     => $isActiveSection,
          'homeSectionInfo'     => $homeSectionInfo,
          'contactAddress'      => $address,
          'contactEmails'       => $emails,
          'contactMobiles'      => $mobiles,
        ]);
      });


      // send this information to both front-end & back-end view files
      View::share(['websiteInfo' => $data]);
    }
  }
}
