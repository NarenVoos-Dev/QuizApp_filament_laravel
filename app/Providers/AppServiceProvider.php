<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\QuizService;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(QuizService::class, function ($app) {
            return new QuizService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            app()->setLocale('es');
        });
    }
}
