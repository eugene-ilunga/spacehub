<?php

namespace App\Traits;

use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\Space;
use PDF;

trait HandlesInvoiceGeneration
{
  public function frontendGenerateInvoice($orderInfo)
  {
    $invoiceName = $orderInfo->booking_number . '.pdf';
    $directory = './assets/file/invoices/space/';
    @mkdir($directory, 0775, true);
    $fileLocation = $directory . $invoiceName;
    $arrData['orderInfo'] = $orderInfo;

    // get system language
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    // get space title
    $arrData['spaceTitle'] = isset($orderInfo['space_id']) ? Space::query()
      ->join('space_contents', 'space_contents.space_id', '=', 'spaces.id')
      ->where('space_contents.language_id', $language->id)
      ->select('space_contents.title', 'space_contents.space_id')
      ->where('spaces.id', $orderInfo['space_id'])->first() : null;

    // generate PDF
    PDF::loadView('frontend.space.invoice', $arrData)->save(public_path($fileLocation));

    return $invoiceName;
  }
  public function backendGenerateInvoice($orderInfo)
  {
    $invoiceName = $orderInfo->booking_number . '.pdf';
    $directory = './assets/file/invoices/space/';
    @mkdir($directory, 0775, true);
    $fileLocation = $directory . $invoiceName;
    $arrData['orderInfo'] = $orderInfo;
    $language = app('currentLanguage');;
    // get space title
    $arrData['spaceTitle'] = isset($orderInfo['space_id']) ? Space::query()
      ->join('space_contents', 'space_contents.space_id', '=', 'spaces.id')
      ->where('space_contents.language_id', $language->id)
      ->select('space_contents.title', 'space_contents.space_id')
      ->where('spaces.id', $orderInfo['space_id'])->first() : null;

    $arrData['language'] = $language;
    $arrData['websiteInfo'] =  app('websiteSettings');;

    // get package title
    PDF::loadView('admin.add-booking.invoice', $arrData)->save(public_path($fileLocation));
    return $invoiceName;
  }
}
