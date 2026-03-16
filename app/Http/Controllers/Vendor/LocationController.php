<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCountries(Request $request)
    {

        $languageId = $request->language_id;
        $perPage = $request->get('per_page', 10);

        $countries = \App\Models\Country::where([
            ['language_id', $languageId],
            ['status', 1],
        ])->paginate($perPage);

        return response()->json([
            "results" => $countries->items(),
            "pagination" => [
                "more" => $countries->hasMorePages()
            ]
        ]);
    }

    public function getStates(Request $request)
    {
        $languageId = $request->language_id;
        $countryId = $request->countryId;
        $perPage = $request->get('per_page', 10);

        $query = \App\Models\State::where([
            ['language_id', $languageId],
            ['status', 1],
        ]);

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        $states = $query->paginate($perPage);

        return response()->json([
            "results" => $states->items(),
            "pagination" => [
                "more" => $states->hasMorePages()
            ]
        ]);
    }

    public function getCities(Request $request)
    {
       
        $languageId = $request->language_id;
        $countryId = $request->countryId;
        $stateId = $request->stateId;
        $perPage = $request->get('per_page', 10);

        $query = \App\Models\City::where([
            ['language_id', $languageId],
            ['status', 1],
        ]);

        if ($stateId) {
            $query->where('state_id', $stateId);
        } elseif ($countryId) {
            $query->where('country_id', $countryId);
        }

        $cities = $query->paginate($perPage);
        return response()->json([
            "results" => $cities->items(),
            "pagination" => [
                "more" => $cities->hasMorePages()
            ]
        ]);
    }
    
}
