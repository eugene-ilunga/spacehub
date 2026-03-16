<?php

namespace App\Http\Middleware;

use App\Http\Helpers\SellerPermissionHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DowngradeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request,  Closure $next , $type = null)
    {
 
      // Check if the user is an admin
      if ($request->input('seller_id') == 'admin') {
        $seller_id = 0 ;
      }
      elseif ($request->input('seller_id') !== 'admin'){
        $seller_id = $request->input('seller_id');
      }
      else {

        $seller_id = Auth::guard('seller')->user()->id;
      }
  
      if ($seller_id == 0) {
        return $next($request);
      }
    
      $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
      if($currentPackage){
        $remainingSpace = SellerPermissionHelper::spaceCount($seller_id);
        $totalService = SellerPermissionHelper::serviceCount($seller_id);
        
        $totalSliderImage = SellerPermissionHelper::sliderImageCount($seller_id);
        $totalOptions = SellerPermissionHelper::optionCount($seller_id);
        $remainingAmenities = SellerPermissionHelper::amenitiesCount($seller_id);
        if($remainingSpace == 'downgraded' || count($remainingAmenities) > 0 || count($totalSliderImage) > 0 || count($totalService) > 0 || count($totalOptions) > 0)
        {
          if($type === 'withAjax'){
          
            return response()->json(['status' => 'downgrade'], 200);
          }
          elseif ($type === 'withoutAjax'){
            
            // this session is used in the scripts.blade to display modal in the seller panel
            session()->put('modal-display', true);

            return redirect()->back()->with('warning', __('Your feature limit is over or down graded') . '!');
          }
        }
        else{
          return $next($request);
        }

      }
      else
      {
        Session::flash('warning', __('Please purchase a new package or extend your current package') . '.');
        return response()->json(['status' => 'success'], 200);
      }
        return $next($request);
    }
}
