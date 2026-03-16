<?php

namespace App\Http\Middleware;

use App\Http\Helpers\SellerPermissionHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('seller')->check()) {
            $seller_id = Auth::guard('seller')->user()->id;;
            $permissions = SellerPermissionHelper::currentPackagePermission($seller_id);

            if (!empty($permissions)) {
                $feature = json_decode($permissions->package_feature, true);
                if (in_array('Hourly Rental', $feature) && in_array('Fixed Timeslot Rental', $feature)) {

                    return $next($request);
                }
            }
        }
        session()->flash('warning', 'Your package does not have permission to access this resource');
        return redirect()->route('vendor.dashboard');
    }
}
