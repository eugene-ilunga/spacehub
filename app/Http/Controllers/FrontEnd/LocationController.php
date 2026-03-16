<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCountries(Request $request)
    {
        
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');
        $languageId = $request->input('language_id');

        $query = Country::query()
            ->select('id', 'name')
            ->where('status', 1); // Only active countries

        if ($languageId) {
            $query->where('language_id', $languageId);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $countries = $query->orderBy('name')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'results' => $countries->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name
                ];
            })->toArray(),
            'pagination' => [
                'more' => $countries->hasMorePages()
            ]
        ]);
    }

    public function getStates(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');
        $languageId = $request->input('language_id');
        $countryId = $request->input('country_id');

        $query = State::query()
            ->select('id', 'name')
            ->where('status', 1); // Only active states

        if ($languageId) {
            $query->where('language_id', $languageId);
        }

        if ($countryId && $countryId !== ' ') {
            $query->where('country_id', $countryId);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $states = $query->orderBy('name')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'results' => $states->map(function ($state) {
                return [
                    'id' => $state->id,
                    'name' => $state->name
                ];
            })->toArray(),
            'pagination' => [
                'more' => $states->hasMorePages()
            ]
        ]);
    }

    public function getCities(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');
        $languageId = $request->input('language_id');
        $countryId = $request->input('country_id');
        $stateId = $request->input('state_id');

        $query = City::query()
            ->select('id', 'name')
            ->where('status', 1); // Only active cities

        if ($languageId) {
            $query->where('language_id', $languageId);
        }

        if ($countryId && $countryId !== ' ') {
            $query->where('country_id', $countryId);
        }

        if ($stateId && $stateId !== ' ') {
            $query->where('state_id', $stateId);
        }

        $cities = $query->orderBy('name')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'results' => $cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name
                ];
            })->toArray(),
            'pagination' => [
                'more' => $cities->hasMorePages()
            ]
        ]);
    }
}
