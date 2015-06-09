<?php

namespace KDuma\Permissions\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use KDuma\Permissions\Helpers\PermissionsHelper;

class Role
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    /**
     * @var PermissionsHelper
     */
    protected $permissionsHelper;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @param PermissionsHelper $permissionsHelper
     */
    public function __construct(Guard $auth, PermissionsHelper $permissionsHelper)
    {
        $this->auth = $auth;
        $this->permissionsHelper = $permissionsHelper;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if(!empty($roles) && $this->auth->check())
        {
            $roles = explode(':', $roles);

            if ($this->permissionsHelper->is($roles)) {
                return $next($request);
            }
            return abort(403);
        }
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->guest('auth/login');
        }
    }
}
