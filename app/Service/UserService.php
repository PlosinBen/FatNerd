<?php

namespace App\Service;

use App\Repository\User\UserRepository;

class UserService
{
    public function login(string $provider, \Laravel\Socialite\Contracts\User $socialUser)
    {
        $userRepository = app(UserRepository::class);

        $user = $userRepository->get($provider, $socialUser->getId());

        if ($user === null) {
            $user = $userRepository->createBySocialite($provider, $socialUser);
        }

        auth()->login($user);
    }
}
