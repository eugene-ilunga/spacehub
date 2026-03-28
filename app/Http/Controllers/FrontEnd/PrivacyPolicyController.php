<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()
            ->select('meta_keyword_contact as meta_keywords', 'meta_description_contact as meta_description')
            ->first();

        $queryResult['breadcrumb'] = $misc->getBreadcrumb();

        return view('frontend.privacy-policy', $queryResult);
    }
}
