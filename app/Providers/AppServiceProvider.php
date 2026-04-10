<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['front.*'], \App\Http\View\Composers\FrontComposer::class);
        view()->composer(['user-front.*'], \App\Http\View\Composers\UserFrontComposer::class);
        view()->composer(['admin.*'], \App\Http\View\Composers\AdminComposer::class);
        view()->composer(['user.*'], \App\Http\View\Composers\UserComposer::class);
        view()->composer(['errors.*'], \App\Http\View\Composers\AdminComposer::class);
    }
}
