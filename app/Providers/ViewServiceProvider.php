<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share user profile picture with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $profile = Profile::where('user_id', Auth::id())->first();
                $view->with('userProfile', $profile);
            }
        });
    }

    public function register()
    {
        //
    }
}
