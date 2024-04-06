<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;

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
    public function boot(Dashboard $dashboard): void
    {
        Model::preventLazyLoading(!$this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(!$this->app->isProduction());

        URL::forceScheme('https');

        JsonResource::withoutWrapping();

//        $dashboard->registerResource('scripts', [
//            'https://code.jquery.com/jquery-3.7.1.min.js',
//            asset('assets/js/rater.min.js'),
//            asset('dashboard/js/app.js'),
//        ]);
    }
}
