<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateToken
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
        if (!isset($request->api_key)) {
            return response()->json([
                'code' => 401,
                'message' => 'No se ha proporcionado una llave de API'
            ]);
        } else {
            $user = \App\Models\Old\Users::userByApiToken($request->api_key);
            if (!$user) {
                return response()->json([
                    'code' => 401,
                    'message' => 'La llave API proporcionada no es válida'
                ]);
            } else {
                $request->user = $user;
                return $next($request);
            }
        }
    }
}
