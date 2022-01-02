<?php

namespace App\Repository\User;

use App\Contract\Repository;
use App\Models\UserAuth;

class UserAuthRepository extends Repository
{
    protected $model = UserAuth::class;

    public function get(string $provider, string $providerUserId, array $data)
    {
        return UserAuth::updateOrCreate([
            'provider' => $provider,
            'provider_user_id' => $providerUserId
        ], [
            'data' => $data
        ]);
    }

    public function GetUser(string $provider, string $providerUserId, array $data)
    {

    }
}
