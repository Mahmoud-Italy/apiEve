<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class BelongsTo
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // check if item belongs to user
        if($request->isMethod('PUT') || $request->isMethod('DELETE')) {
            $id      = decrypt(request('id'));
            $user_id = auth()->guard('api')->user()->id;
            if(!DB::table('events')->where('id', $id)->where('user_id', $user_id)->count()) {
                return response()->json(['error' => 'This entry not belongs to you.'], 503);
            }
        }
        

        return $next($request);
    }
}
