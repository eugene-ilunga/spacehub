<?php

namespace App\Http\Middleware;

use App\Http\Helpers\SellerPermissionHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketMiddleware
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
        $supportTicket = json_decode($permissions->package_feature, true);
        if ($supportTicket == null || !in_array('Support Tickets', $supportTicket)) {
          session()->flash('warning', __('Your package does not have permission to access this resource') . '');
          return redirect()->route('vendor.dashboard');
        }
      }
    }
    return $next($request);
  }
}
