<?php

namespace Modules\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BotCMSAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Find current site based on host (fallback to ID 1)
        $domain = $request->getHost();
        $site = DB::table('sites')->where('domain', $domain)->first();
        if (!$site) {
            $site = DB::table('sites')->find(1);
        }

        if (!$site || !$site->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'The requested site is inactive or not found.']);
        }

        // Share current site ID and details on the request object for easy access
        $request->attributes->set('current_site', $site);
        $request->attributes->set('current_site_id', $site->id);

        // Get user's role on this specific site
        $siteUser = DB::table('site_user')
            ->where('site_id', $site->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$siteUser) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'You do not have access to this site dashboard.']);
        }

        // Attach role to the user object temporarily for easy checking
        $role = DB::table('roles')->find($siteUser->role_id);
        $user->current_role = $role;

        // If a permission is requested, verify if the role has it
        if ($permission) {
            $hasPermission = DB::table('permission_role')
                ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                ->where('permission_role.role_id', $role->id)
                ->where('permissions.slug', $permission)
                ->exists();

            if (!$hasPermission) {
                abort(403, 'Unauthorized action. You do not have the required permission: ' . $permission);
            }
        }

        return $next($request);
    }
}
