<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->name('api.')
                ->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->redirectGuestsTo(function (Request $request) {
            // When accessing admin area, go to admin login
            if ($request->is('admin*') || $request->routeIs('admin.*') || $request->routeIs('admin-login')) {
                return route('admin-login');
            }

            // Otherwise go to user login
            return route('login');
        });

        // $middleware->redirectUsersTo(function (Request $request) {
        //     /**
        //      * IMPORTANT:
        //      * If the request is for admin pages/login, do NOT redirect just because web user is logged in.
        //      * Only redirect if admin guard is already logged in.
        //      */
        //     if ($request->is('admin*') || $request->routeIs('admin.*') || $request->routeIs('admin-login')) {
        //         return auth()->guard('admin')->check()
        //             ? route('super_admin.dashboard')
        //             : null; // allow admin login even when web user is logged in
        //     }

        //     // For normal (web) guest pages, redirect logged-in web users
        //     $user = auth()->guard('web')->user();
        //     if (! $user) return route('login');

        //     if ($user->hasRole('practice')) return route('directory');
        //     if ($user->hasRole('specialist')) return route('discoverjob');

        //     return route('discoverjob'); // fallback for web users
        // });

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ])->validateCsrfTokens(except: [
            'payment/success',
            'payment/fail',
            'payment/cancel',
            'payment/ipn',
            'payment/stripe/webhook',
        ])->alias([
            'precognitive' => HandlePrecognitiveRequests::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->statefulApi();
        $middleware->group('api', [
            \Illuminate\Session\Middleware\StartSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(static function (Throwable $e) {
            if (app()->bound('honeybadger')) {
                app('honeybadger')->notify($e, app('request'));
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 404,
                    'message' => 'The resource cannot be found.',
                ]);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.',
                ]);
            }
        });

    })->create();
