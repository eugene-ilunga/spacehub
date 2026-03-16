<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasPermission
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */


  public function handle(Request $request, Closure $next, ...$menuNames)
  {
    $authAdmin = Auth::guard('admin')->user();
    $role = null;

    if (!is_null($authAdmin->role_id)) {
      $role = $authAdmin->role()->first();
    }

    if (!is_null($role)) {
      $rolePermissions = json_decode($role->permissions) ?? [];
    }
    else{
      $rolePermissions = [];
    }

    $groupPermissions = config('admin_sidebar_permissions', []);


    if (is_null($role) || empty($menuNames)) {
      return $next($request);
    }

    foreach ($menuNames as $requiredPermission) {
      if (in_array($requiredPermission, $rolePermissions)) {
        return $next($request);
      }

      // Check group permissions
      foreach ($groupPermissions as $groupData) {
        // Skip if permission isn't in this group's children
        if (!in_array($requiredPermission, $groupData['children'])) {
          continue;
        }

        // Grant access if parent permission exists
        if (in_array($groupData['parent'], $rolePermissions)) {
          return $next($request);
        }
      }
    }

    return redirect()->route('admin.dashboard')->with('warning', __('You do not have permission to access that page.'));
  }


}
