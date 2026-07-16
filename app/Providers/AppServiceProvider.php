<?php

namespace App\Providers;

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
        // Share pending mechanics count with all admin views
        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            $view->with('pendingMechanicsCount',
                \App\Models\Mechanic::where('verification_status', 'pending')->count()
            );
        });
    }
}
