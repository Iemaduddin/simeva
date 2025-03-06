<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        // Tangani 403 Forbidden
        Response::macro('forbidden', function () {
            return response()->view('errors.403', [], 403);
        });

        // Tangani 404 Not Found
        Response::macro('notFound', function () {
            return response()->view('errors.404', [], 404);
        });

        // Fallback jika URL tidak ditemukan (otomatis jadi 404)
        app('router')->fallback(function () {
            return Response::notFound();
        });
    }
}