<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\City;
use App\Models\Country;
use App\Models\Language;
use App\Models\SpaceContent;
use App\Models\State;
use App\Rules\ImageDimensions;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LocationManagementController extends Controller
{
  public function indexCountry(Request $request)
  {
    $name = $code = null;
    if ($request->filled('name')) {
      $name = $request['name'];
    }
    $language = getAdminLanguage();
    $data['language'] = $language;

    $query = $language->country()->orderByDesc('id');

    if ($name) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    $data['countries'] = $query->paginate(10);
    $data['langs'] = Language::all();
    return view('admin.location-management.country.index', $data);
  }

  public function storeCountry(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => [
        'required',
        Rule::unique('countries')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store image in storage

    Country::query()->create($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', __('New country added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateCountry(Request $request)
  {

    $country = Country::query()->find($request->id);

    $rules = [
      'image' => $request->hasFile('image') ? new ImageMimeTypeRule() : '',
      'name' => [
        'required',
        Rule::unique('countries')->where(function ($query) use ($country) {
          return $query->where('language_id', $country->language_id);
        })->ignore($request->id)
      ],
      'status' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }


    $country->update($request->except('slug') + [

      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', __('Country updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyCountry(Request $request)
  {
    $id = $request->id;
    $this->deleteCountry($id);

    return redirect()->back()->with('success', __('Country deleted successfully') . '!');
  }

  public function bulkDestroyCountry(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteCountry($id);
    }

    $request->session()->flash('success', __('Countries deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteCountry($id)
  {
    $country = Country::query()->findOrFail($id);
    $states = $country->states()->get();
    if (count($states) > 0) {
      foreach ($states as $state) {
        $cities = $state->cities()->get();

        if (count($cities) > 0) {
          // delete space content according to city
          foreach ($cities as $city)
            $spaceContents = SpaceContent::where('city_id', $city->id)->get();
          if (count($spaceContents) > 0) {
            foreach ($spaceContents as $spaceContent) {
              $spaceContent->delete();
            }
          }
          $imagePath = !empty($category->icon_image) ? public_path('assets/img/city/' . $city->image) : null;
          if (!empty($imagePath) && file_exists($imagePath)) {
            @unlink($imagePath);
          }
          $city->delete();
        }
        // delete space content according to state
        $spaceContents = SpaceContent::where('state_id', $state->id)->get();
        if (count($spaceContents) > 0) {
          foreach ($spaceContents as $spaceContent) {
            $spaceContent->delete();
          }
        }
        $state->delete();
      }
    }

    // delete city according to country
    $cities = City::where('country_id', $id)->get();
    if ($cities->isNotEmpty()) {
      // delete space content according to city
      foreach ($cities as $city)
        $spaceContents = SpaceContent::where('city_id', $city->id)->get();
      if ($spaceContents->isNotEmpty()) {
        foreach ($spaceContents as $spaceContent) {
          $spaceContent->delete();
        }
      }
      $imagePath = !empty($category->icon_image) ? public_path('assets/img/city/' . $city->image) : null;
      if (!empty($imagePath) && file_exists($imagePath)) {
        @unlink($imagePath);
      }
      $city->delete();
    }
    // delete space content according to country
    $spaceContents = SpaceContent::where('country_id', $country->id)->get();
    if (count($spaceContents) > 0) {
      foreach ($spaceContents as $spaceContent) {
        $spaceContent->delete();
      }
    }
    $country->delete();
  }

  //state section start
  public function indexState(Request $request)
  {
    $name = $country = null;
    if ($request->filled('name')) {
      $name = $request['name'];
    }
    if ($request->filled('country')) {
      $country = $request['country'];
    }
    

    $language = getAdminLanguage();
    $languageId = $language->id;
    $data['language'] = $language;

    $countryId = Country::where([
      ['language_id', $languageId],
      ['name', 'like', '%' . $country . '%'],
    ])->first()->id ?? null;


    $data['states'] = State::where(DB::raw('states.language_id'), $languageId)
      ->leftJoin('countries', function ($join) use ($languageId) {
        $join->on('states.country_id', '=', 'countries.id')
          ->where(DB::raw('countries.language_id'), $languageId);
      })
      ->when($name, function ($query) use ($name) {
        $query->where('states.name', 'like', '%' . $name . '%');
      })
      ->when($country, function ($query) use ($countryId) {
        $query->where('states.country_id', '=', $countryId);
      })
      ->select('states.*', 'countries.id as country_id', 'countries.name as country_name')
      ->paginate(10);

    $data['langs'] = Language::all();

    return view('admin.location-management.state.index', $data);
  }

  // get countries according language ID FOR STATE

  public function getCountries(Request $request)
  {
    $languageId = $request->get('languageId');
    $page = $request->get('page', 1);
    $limit = $request->get('limit', 10);
    $countryId = $request->get('id');

    if ($countryId) {
      // Fetch a specific country by ID
      $countries = Country::where('language_id', $languageId)
        ->where('id', $countryId)
        ->get();
    } else {
      // Fetch paginated countries using paginate method
      $countries = Country::where('language_id', $languageId)
        ->paginate($limit, ['*'], 'page', $page);
    }

    // Fetch states if needed (optional, based on your requirements)
    $states = State::where('language_id', $languageId)->get();

    if ($countries->isEmpty()) {
      return response()->json([
        'countries' => [],
        'states' => [],
        'error' => __('No countries found for the selected language') . '.'
      ], 200);
    }

    if ($states->isEmpty()) {
      return response()->json([
        'countries' => $countryId ? $countries : $countries->items(),
        'states' => [],
        'error' => __('No states found for the selected country and language') . '.'
      ], 200);
    }

    return response()->json([
      'countries' => $countryId ? $countries : $countries->items(),
      'states' => $states,
      'pagination' => $countryId ? null : [
        'more' => $countries->hasMorePages()
      ]
    ], 200);
  }


  public function storeState(Request $request)
  {

    $countryExists = Country::where('language_id', $request->language_id)->exists();
    $rules = [
      'language_id' => 'required',
      'country_id' => $countryExists ? 'required' : '',
      'name' => [
        'required',
        Rule::unique('states')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],

      'status' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store image in storage

    State::query()->create($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', __('New State added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateState(Request $request)
  {

    $state = State::query()->find($request->id);
    $countryExists = Country::where('language_id', $state->language_id)->exists();

    $rules = [
      'name' => [
        'required',
        Rule::unique('states')->where(function ($query) use ($state) {
          return $query->where('language_id', $state->language_id);
        })->ignore($request->id)
      ],
      'status' => 'required|numeric',
      'country_id' => $countryExists ? 'required' : '',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }


    $state->update($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', __('State updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function bulkDestroyState(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteState($id);
    }

    $request->session()->flash('success', __('States deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyState(Request $request)
  {
    $id = $request->id;
    $this->deleteState($id);
    return redirect()->back()->with('success', __('State deleted successfully') . '!');
  }

  public function deleteState($id)
  {
    $state = State::where('id', $id)->firstOrFail();
    if ($state) {
      $spaceContents = SpaceContent::where('city_id', $state->id)->get();
      if (count($spaceContents) > 0) {
        foreach ($spaceContents as $spaceContent) {
          $spaceContent->delete();
        }
      }
    }
    $state->delete();
  }


  //city section start

  public function indexCity(Request $request)
  {
    $name = $country = $state = null; 
    if ($request->filled('name')) {
      $name = $request['name'];
    }
    if ($request->filled('state')) {
      $state = $request['state'];
    }
    if ($request->filled('country')) {
      $country = $request['country'];
    }

    $language = getAdminLanguage();
    $languageId = $language->id;
    $data['language'] = $language;

    $countryId = Country::where([
      ['language_id', $languageId],
      ['name', 'like', '%' . $country . '%'],
    ])->first()->id ?? null;

    $stateId = State::where([
      ['language_id', $languageId],
      ['name', 'like', '%' . $state . '%'],
    ])->first()->id ?? null;

    $data['cities'] = City::where(DB::raw('cities.language_id'), $languageId)
      ->leftJoin('states', function ($join) use ($languageId) {
        $join->on('cities.state_id', '=', 'states.id')
          ->where(DB::raw('states.language_id'), $languageId);
      })
      ->leftJoin('countries', function ($join) use ($languageId) {
        $join->on('cities.country_id', '=', 'countries.id')
          ->where(DB::raw('countries.language_id'), $languageId);
      })
      ->when($name, function ($query) use ($name) {
        $query->where('cities.name', 'like', '%' . $name . '%');
      })
      ->when($country, function ($query) use ($countryId) {
        $query->where('cities.country_id', '=', $countryId);
      })
      ->when($state, function ($query) use ($stateId) {
        $query->where('cities.state_id', '=', $stateId);
      })
      ->select('cities.*', 'states.id as state_id', 'states.name as state_name', 'countries.id as country_id', 'countries.name as country_name')
      ->orderBy('cities.id', 'desc')
      ->paginate(10);

    $data['langs'] = Language::all();
    return view('admin.location-management.city.index', $data);
  }
  public function updateCityFeaturedStatus(Request $request, $id)
  {
    $city = City::find($id);

    if ($request->has('is_featured')) {
      $city->is_featured = $request->input('is_featured');
      $city->save();

      if ($city->is_featured) {
        $request->session()->flash('success', __('City featured successfully') . '!');
      } else {
        $request->session()->flash('success', __('City unfeatured successfully') . '!');
      }
    }

    return redirect()->back();
  }


  public function storeCity(Request $request)
  {

    $countryId = $request->input('country_id');
    $countryExists = Country::where('language_id', $request->language_id)->exists();
    $stateExists = false;
    $themeVersion = Basic::select('theme_version')->first();

    // Determine if states exist for the given country
    if ($countryId) {
      $stateExists = State::where('country_id', $countryId)->exists();
    }

    $rules = [
      'language_id' => 'required',
      'country_id' => $countryExists ? 'required' : '',
      'state_id' => $stateExists ? 'required' : '',
      'name' => [
        'required',
        Rule::unique('cities')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required',
    ];

    // Apply different image dimension rules based on theme version
    if ($themeVersion->theme_version == 1) {
      $rules['image'] = [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(740, 760, 850, 860) // Theme 1 dimensions
      ];
    } elseif ($themeVersion->theme_version == 2) {
      $rules['image'] = [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(740, 760, 440, 460) // Theme 2 dimensions
      ];
    } elseif($themeVersion->theme_version == 3) {
      $rules['image'] = [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(1000, 1100, 650, 700) // Default/Theme 3 dimensions
      ];
    }
    else{
      $rules['image'] = [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(740, 760, 850, 860) // default vesion Theme 1 dimensions
      ];
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store image in storage
    $imgName = UploadFile::store('./assets/img/city/', $request->file('image'));

    City::query()->create($request->except('image', 'slug') + [
      'image' => $imgName,
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', __('New city added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateCity(Request $request)
  {
    $city = City::query()->find($request->id);
    $themeVersion = Basic::select('theme_version')->first();
    $rules = [
      'name' => [
        'required',
        Rule::unique('cities')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required',
    ];

    if($request->hasFile('image')){
      // Apply different image dimension rules based on theme version
      if ($themeVersion->theme_version == 1) {
        $rules['image'] = [
          'required',
          new ImageMimeTypeRule(),
          new ImageDimensions(740, 760, 850, 860) // Theme 1 dimensions
        ];
      } elseif ($themeVersion->theme_version == 2) {
        $rules['image'] = [
          'required',
          new ImageMimeTypeRule(),
          new ImageDimensions(740, 760, 440, 460) // Theme 2 dimensions
        ];
      } elseif ($themeVersion->theme_version == 3) {
        $rules['image'] = [
          'required',
          new ImageMimeTypeRule(),
          new ImageDimensions(1000, 1100, 650, 700) // Theme 3 dimensions
        ];
      } else {
        $rules['image'] = [
          'required',
          new ImageMimeTypeRule(),
          new ImageDimensions(740, 760, 850, 860) // default vesion Theme 1 dimensions
        ];
      }
    }

    // Check if the country_id is provided
    if ($request->has('country_id')) {
      $rules['country_id'] = ['required', 'numeric'];

      // Check if there are states associated with the provided country_id
      $statesExist = State::where('country_id', $request->input('country_id'))->exists();

      if ($statesExist) {
        $rules['state_id'] = ['required', 'numeric'];
      }
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }
    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $city->image;
      if (!empty($oldImage) && file_exists(public_path('assets/img/city/' . $oldImage))) {
        unlink(public_path('assets/img/city/' . $oldImage));
      }
      $imgName = UploadFile::update('./assets/img/city/', $newImage, $oldImage);
    }

    // store image in storage
    $city->update($request->except('image', 'slug') + [
      'image' => isset($imgName) ? $imgName : $city->image,
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', __('City updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  //get state by country for edit section in location management file

  public function getStatesByCountry(Request $request)
  {
    
    $countryId = $request->input('countryId');
    $languageId = $request->input('languageId');
    $stateId = $request->input('stateId'); 
    $perPage = $request->input('perPage', 10); 
    $page = $request->input('page', 1);
    
    if ($stateId) {
      // Fetch a single state by ID (for pre-selection)
      $state = State::where([
        ['language_id', $languageId],
        ['country_id', $countryId],
        ['id', $stateId]
      ])->first();

      if (!$state) {
        return response()->json([
          'states' => [],
          'hasMore' => false,
          'error' => __('State not found for the selected country and language') . '.'
        ], 200);
      }

      return response()->json([
        'states' => [['id' => $state->id, 'name' => $state->name]],
        'hasMore' => false
      ], 200);
    }

    // Paginated fetch for infinite scrolling
    $states = State::where([
      ['language_id', $languageId],
      ['country_id', $countryId],
    ])->paginate($perPage, ['id', 'name'], 'page', $page);

    if ($states->isEmpty()) {
      return response()->json([
        'states' => [],
        'hasMore' => false,
        'error' => __('No states found for the selected country and language') . '.'
      ], 200);
    }

    return response()->json([
      'states' => $states->items(),
      'hasMore' => $states->hasMorePages()
    ], 200);
  }

  public function destroyCity(Request $request)
  {
    $id = $request->id;
    $this->deleteCity($id);

    return redirect()->back()->with('success', __('City deleted successfully') . '!');
  }

  public function bulkDestroyCity(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteCity($id);
    }

    $request->session()->flash('success', __('Cities deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteCity($id)
  {
    $city = City::where('id', $id)->firstOrFail();
    if ($city) {
      $spaceContents = SpaceContent::where('city_id', $city->id)->get();
      if (count($spaceContents) > 0) {
        foreach ($spaceContents as $spaceContent) {
          $spaceContent->delete();
        }
      }
    }
    $imagePath = !empty($category->icon_image) ? public_path('assets/img/city/' . $city->image) : null;
    if (!empty($imagePath) && file_exists($imagePath)) {
      @unlink($imagePath);
    }
    $city->delete();
  }


  //get state,country, city for edit section in space management file
  public function getStatesByCountryForSpace(Request $request)
  {

    $states = State::where([
      ['country_id', $request->countryId],
      ['language_id', $request->language_id],
    ])
      ->where('status', 1)
      ->get();

    return response()->json($states);
  }

  public function getCitiesByCountryForSpace(Request $request)
  {

    $stateId = $request->input('stateId');
    $countryId = $request->input('countryId');
    $langId = $request->input('language_id');

    $cities = City::where(function ($query) use ($stateId, $countryId, $langId) {
      if ($stateId) {
        $query->where([
          ['state_id', $stateId],
          ['language_id', $langId]
        ]);
      } else {
        $query->where([
          ['country_id', $countryId],
          ['language_id', $langId]
        ]);
      }
    })
      ->where('status', 1)
      ->get();

    return response()->json($cities);
  } 
}
