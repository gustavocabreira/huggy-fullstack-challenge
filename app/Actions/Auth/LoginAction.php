<?php

namespace App\Actions\Auth;

use App\DTOs\HuggyUserDTO;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class LoginAction
{
    public function execute($request): array
    {
        $huggyUser = Socialite::driver($request->provider)->stateless()->user();

        $userPayload = (new HuggyUserDTO)->fromHuggy($huggyUser)->toArray();

        $user = User::query()->updateOrCreate(['email' => $huggyUser->email], $userPayload);
        $user->createToken('Huggy')->accessToken;

        return [
            'access_token' => $user->token,
        ];
    }
}
