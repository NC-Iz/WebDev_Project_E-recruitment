<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

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
        // Share user profile with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $profile = Profile::where('user_id', Auth::id())->first();
                $view->with('userProfile', $profile);
            }
        });
    }
}
