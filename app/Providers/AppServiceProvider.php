<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;


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
        // Bagikan data notifikasi ke view navigation
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('unreadNotifications', $user->unreadNotifications);
                $view->with('notifications', $user->notifications()->take(5)->get()); // Ambil 5 notifikasi terakhir
            } else {
                $view->with('unreadNotifications', collect());
                $view->with('notifications', collect());
            }
        });
    }
}
