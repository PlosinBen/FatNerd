<?php

namespace App\Repository\User;

use App\Contract\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends Repository
{
    protected $model = User::class;

    public function get(string $provider, string $providerUserId)
    {
        return $this->getModelInstance()
            ->whereHas('UserAuths', function (Builder $query) use ($provider, $providerUserId) {
                $query->where('provider', $provider)
                    ->where('provider_user_id', $providerUserId);
            })
            ->first();
    }

    public function createBySocialite(string $provider, \Laravel\Socialite\Contracts\User $socialUser)
    {
        $user = $this->insertModel([
            'name' => $socialUser->getName(),
            'avatar' => $socialUser->getAvatar()
        ]);

        $user->UserAuths()->updateOrCreate([
            'provider' => $provider,
            'provider_user_id' => $socialUser->getId()
        ], [
            'data' => $socialUser->getRaw()
        ]);

        return $user;
    }
}
