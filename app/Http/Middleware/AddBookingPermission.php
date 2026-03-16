<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helpers\SellerPermissionHelper;
use Illuminate\Support\Facades\Auth;

class AddBookingPermission
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
        if (Auth::check()) {
            $seller_id = Auth::guard('seller')->user()->id;;
            $permissions = SellerPermissionHelper::currentPackagePermission($seller_id);

            if (!empty($permissions)) {
                $add_booking_feature = json_decode($permissions->package_feature, true);
                if ($add_booking_feature == null || !in_array('Add Booking', $add_booking_feature)) {
                    session()->flash('warning', __('Your package does not have permission to access this resource') . '');
                    return redirect()->route('vendor.dashboard');
                }
            }
        }
        return $next($request);
    }
}
