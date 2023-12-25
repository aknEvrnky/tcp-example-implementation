<?php

namespace App\Providers;

use App\Services\LessonService;
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
        $this->app->singletonIf(LessonService::class, function () {
            $ch = curl_init();
            return new LessonService($ch);
        });
    }
}
