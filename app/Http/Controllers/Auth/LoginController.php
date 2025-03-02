<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->scopes([
            'install_app',
            'read_agent_profile',
        ])->stateless()->redirect();
    }

    public function callback(Request $request, LoginAction $action): RedirectResponse
    {
        $accessToken = $action->execute($request);

        return response()->redirectTo('http://spa.localhost.com/auth/login?access_token='.$accessToken['access_token']);
    }
}
