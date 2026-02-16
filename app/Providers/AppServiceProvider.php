<?php

namespace App\Providers;

use App\Services\UserDataService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share user data with sidebar and topbar
        View::composer(['layouts.sidebar', 'layouts.topbar'], function ($view) {
            $userData = null;
            $accessToken = session('aisite_access_token');

            if ($accessToken) {
                $userDataService = new UserDataService();
                $userData = $userDataService->getUserDetails($accessToken);
            }

            $view->with('userData', $userData);
        });
    }
}
