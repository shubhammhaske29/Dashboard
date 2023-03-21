<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\DB;

class AuthenticateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $error = true;

        $token = $request->header('token');

        $is_user  = User::where('token',$token)->exists();

        if($is_user)
        {
            $error = false;
        }

        if($error)
        {
            return response()->json([
                "status"  => "error",
                "message" => "Token mismatched,Authentication failed.",
            ]);
        }

        return $next($request);
    }
}
