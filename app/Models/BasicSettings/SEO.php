<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
  use HasFactory;

  protected $table = 'seos';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'meta_keyword_home',
    'meta_description_home',
    'meta_keyword_spaces',
    'meta_description_spaces',
    'meta_keyword_space_details',
    'meta_description_space_details',
    'meta_keyword_space_booking',
    'meta_description_space_booking',
    'meta_keyword_pricing',
    'meta_description_pricing',
    'vendor_page_meta_keywords',
    'vendor_page_meta_description',
    'shop_page_meta_keywords',
    'shop_page_meta_description',
    'cart_page_meta_keywords',
    'cart_page_meta_description',
    'shop_checkout_page_meta_keywords',
    'shop_checkout_page_meta_description',
    'meta_keyword_blog',
    'meta_description_blog',
    'meta_keyword_aboutus',
    'meta_description_aboutus',
    'meta_keyword_faq',
    'meta_description_faq',
    'meta_keyword_term_and_condition',
    'meta_description_term_and_condition',
    'meta_keyword_contact',
    'meta_description_contact',
    'meta_keyword_customer_login',
    'meta_description_customer_login',
    'meta_keyword_customer_signup',
    'meta_description_customer_signup',
    'meta_keyword_customer_forget_password',
    'meta_description_customer_forget_password',
    'meta_keyword_vendor_login',
    'meta_description_vendor_login',
    'meta_keyword_vendor_signup',
    'meta_description_vendor_signup',
    'meta_keyword_vendor_forget_password',
    'meta_description_vendor_forget_password',
    'meta_keyword_blog_post_details',
    'meta_description_blog_post_details',
    'vendor_details_page_meta_keywords',
    'vendor_details_page_meta_description',
    'product_details_page_meta_keywords',
    'product_details_page_meta_description'
  ];

  public function seoLang()
  {
    return $this->belongsTo(Language::class);
  }
}
