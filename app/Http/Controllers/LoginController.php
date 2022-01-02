<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function index()
    {
        return $this
            ->title('登入')
            ->view('Login');
    }

    public function show($provider)
    {
        if( env('APP_ENV') === 'local' ) {
            auth()->loginUsingId(1);

            return redirect('/');
        }

        return Inertia::location(
            Socialite::driver($provider)
                ->redirect()
                ->getTargetUrl()
        );
    }

    public function callback($provider, UserService $userService)
    {
        $socialUser = Socialite::driver($provider)
            ->user();

        $userService->login($provider, $socialUser);

        return redirect('/');
    }
}
