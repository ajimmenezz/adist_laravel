<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Old\Users;
use App\Models\User;

class ValidateAdISTToken
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
        if (!$request->has('token')) {
            if ($request->session()->has('token')) {
                $token = session('token');
            } else {
                return redirect(env('ADIST_ORIGIN_URL'));
            }
        } else {
            $token = $request->input('token');
        }

        $user = $this->searchUser($token);
        if (!$user) {
            return redirect(env('ADIST_ORIGIN_URL'));
        }

        $laravel_user = User::where('email', $user->Email_2)->first();
        if (!$laravel_user) {

            User::create([
                'name' => $user->User_name,
                'email' => $user->Email_2,
                'password' => bcrypt($user->Email_2)
            ]);
        }

        session([
            'user' => $user,
            'token' => $token
        ]);

        return $next($request);
    }

    private function searchUser($token)
    {
        return Users::userByApiToken($token);
    }
}
