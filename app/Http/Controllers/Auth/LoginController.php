<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->scopes([
            'install_app',
            'read_agent_profile'
        ])->stateless()->redirect();
    }

    public function callback(Request $request, LoginAction $action): JsonResponse
    {
        $accessToken = $action->execute($request);
        return response()->json($accessToken, Response::HTTP_OK);
    }
}
