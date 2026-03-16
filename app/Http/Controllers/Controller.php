<?php

namespace App\Http\Controllers;

use App\Models\BasicSettings\Basic;
use App\Models\Language;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Mpdf\Mpdf;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function getCurrencyInfo()
  {
    $baseCurrencyInfo = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
      ->firstOrFail();

    return $baseCurrencyInfo;
  }

  public function makeInvoice($data)
  {

    $file_name = uniqid($data['order_id']) . ".pdf";
    
    $save_path = (isset($data['purpose']) && $data['purpose'] == 'feature') 
    ? public_path('assets/file/invoices/space/featured/') . $file_name   
    : public_path('assets/file/invoices/membership/') . $file_name;

    $html = view('pdf.membership', compact('data'))->render();

    // Initialize mPDF with RTL and UTF-8 support
    $mpdf = new Mpdf([
      'mode' => 'utf-8',
      'format' => 'A4',
      'autoScriptToLang' => true,
      'autoLangToFont' => true,
    ]);

    // Write HTML content to PDF
    $mpdf->WriteHTML($html);

    // Directory creation logic with if-else
    if (isset($data['purpose']) && $data['purpose'] == 'feature') {
      if (!file_exists(public_path('assets/file/invoices/space/featured/'))) {
        mkdir(public_path('assets/file/invoices/space/featured/'), 0755, true);
      }
    } else {
      if (!file_exists(public_path('assets/file/invoices/membership/'))) {
        mkdir(public_path('assets/file/invoices/membership/'), 0755, true);
      }
    }

    // Save the PDF file to disk
    $mpdf->Output($save_path, \Mpdf\Output\Destination::FILE);

    return $file_name;
  }
  public function getLanguage()
  {
    // get the current locale of this system
    if (Session::has('lang')) {
      $locale = Session::get('lang');
    }
    if (empty($locale)) {
      $language = Language::where('is_default', 1)->first();
    } else {
      $language = Language::where('code', $locale)->first();
      if (empty($language)) {
        $language = Language::where('is_default', 1)->first();
      }
    }

    return $language;
  }
}
