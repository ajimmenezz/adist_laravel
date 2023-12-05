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

        $url = $request->getPathInfo();

        if (str_starts_with($url, '/api/v3')) {
            $token = $this->getTokenFromHeader($request);
            if (!$token) {
                return $this->unauthorized();
            }

            $user = $this->getUserFromToken($request, $token);
            if (!$user) {
                return $this->unauthorized('La llave API proporcionada no es válida');
            } else {
                $request->user = $user;
                return $next($request);
            }
        }

        if (str_starts_with($url, '/api/v2')) {
            return $next($request);
        }

        if (!isset($request->api_key)) {
            return $this->unauthorized();
        } else {
            $user = $this->getUserFromToken($request, $request->api_key);
            if (!$user) {
                return $this->unauthorized('La llave API proporcionada no es válida');
            } else {
                $request->user = $user;
                return $next($request);
            }
        }
    }

    private function getTokenFromHeader(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');

        if ($authorizationHeader && preg_match('/Bearer\s+(.*)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
            return $token;
        }

        return null;
    }

    private function getUserFromToken(Request $request, $token)
    {
        $user = \App\Models\Old\Users::userByApiToken($token);
        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'code' => 401,
            'message' => $message ? $message : 'No se ha proporcionado una llave de API'
        ], 401);
    }
}
