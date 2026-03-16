<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageStoreRequest;
use App\Http\Requests\PackageUpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Mews\Purifier\Facades\Purifier;

class PackageController extends Controller
{
  public function settings()
  {
    $data['abe'] = Basic::query()->select('expiration_reminder')->first();
    return view('admin.packages.settings', $data);
  }

  public function features()
  {
    $be = Basic::query()->select('package_features')->first();
    $features = json_decode($be->package_features, true);
    $data['features'] = $features;
    return view('admin.packages.features', $data);
  }

  public function updateFeatures(Request $request)
  {
    $features = $request->features ? json_encode($request->features) : [];
    $bes = Basic::all();
    foreach ($bes as $key => $be) {
      $be->package_features = $features;
      $be->save();
    }
    Session::flash('success', __('Package features updated successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  public function updateSettings(Request $request)
  {
    $request->validate(
      [
        'expiration_reminder' => 'required'
      ],
    );
    $be = Basic::first();
    $be->expiration_reminder = $request->expiration_reminder;
    $be->save();
    Session::flash('success', __('Settings updated successfully') . '!');
    return back();
  }

  /**
   * Display a listing of the resource.
   *
   *
   */
  public function index(Request $request)
  {
    if (session()->has('lang')) {
      $currentLang = Language::where('code', session()->get('lang'))->first();
    } else {
      $currentLang = Language::where('is_default', 1)->first();
    }
    $search = $request->search;
    $data['bex'] = $currentLang->basic_extended;
    $data['packages'] = Package::query()->when($search, function ($query, $search) {
      return $query->where('title', 'like', '%' . $search . '%');
    })->where('id', '<>', 999999)->orderBy('created_at', 'DESC')->get();
    return view('admin.packages.index', $data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   */
  public function store(PackageStoreRequest $request)
  {

    try {
      $features = json_encode($request->features);
      $in = $request->all();
      $in['package_feature'] = $features;
      $in['slug'] = createSlug($request->title);
      $in['custom_features'] = Purifier::clean($request->custom_features);
      Package::create($in);
      Session::flash('success', "Package Created Successfully");
      return Response::json(['status' => 'success'], 200);
    } catch (\Throwable $e) {
      return $e;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return
   */
  public function edit($id)
  {

    if (session()->has('lang')) {
      $currentLang = Language::where('code', session()->get('lang'))->first();
    } else {
      $currentLang = Language::where('is_default', 1)->first();
    }
    $data['bex'] = $currentLang->basic_extended;
    $data['package'] = Package::query()->findOrFail($id);

    return view("admin.packages.edit", $data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   *
   */
  public function update(PackageUpdateRequest $request)
  {

    try {
      $in = $request->all();
      if (!array_key_exists('is_trial', $in)) {
        $request['is_trial'] = "0";
        $request['trial_days'] = 0;
      }

      if (!empty($request->features)) {
        $in['package_feature'] = json_encode($request->features);
      } else {
        $in['package_feature'] = null;
      }

      $in["custom_features"] = Purifier::clean($request["custom_features"]);

      Package::query()->findOrFail($request->package_id)->update($in);
      Session::flash('success', "Package Update Successfully");
      return Response::json(['status' => 'success'], 200);
    } catch (\Throwable $e) {
      return Response::json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  public function delete(Request $request)
  {
    try {
      $package = Package::query()->findOrFail($request->package_id);

      // Delete associated memberships
      if ($package->memberships()->count() > 0) {
        foreach ($package->memberships as $key => $membership) {
          @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
          $membership->delete();
        }
      }

      // Update admin membership if it's associated with the deleted package
      $admin_membership = Membership::where('seller_id', 0)->first();
      if ($admin_membership && $admin_membership->package_id == $package->id) {
        $lifetime_package = Package::where('term', 'lifetime')->first();
        if (!$lifetime_package) {
          $lifetime_package = Package::first();
        }
        $admin_membership->package_id = $lifetime_package->id;
        $admin_membership->save();
      }

      // Delete the package
      $package->delete();

      Session::flash('success', 'Package deleted successfully!');
      return back();
    } catch (\Throwable $e) {
      return Response::json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
  }

  public function bulkDelete(Request $request)
  {

    try {
      $ids = $request->ids;

      foreach ($ids as $id) {
        $package = Package::query()->findOrFail($id);

        // Delete associated memberships
        if ($package->memberships()->count() > 0) {
          foreach ($package->memberships as $key => $membership) {
            @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
            $membership->delete();
          }
        }

        // Update admin membership if it's associated with the deleted package
        $admin_membership = Membership::where('seller_id', 0)->first();
        if ($admin_membership && $admin_membership->package_id == $package->id) {
          $lifetime_package = Package::where('term', 'lifetime')->first();
          if (!$lifetime_package) {
            $lifetime_package = Package::first();
          }
          $admin_membership->package_id = $lifetime_package->id;
          $admin_membership->save();
        }

        // Delete the package
        $package->delete();
      }

      Session::flash('success', 'Package bulk deletion is successful!');
      return response()->json(['status' => 'success'], 200);
    } catch (\Throwable $e) {
      return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
  }
}
