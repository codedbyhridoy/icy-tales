<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        /** @var Kernel $kernel */
        $kernel = app()->make(Kernel::class);


        Vite::prefetch(concurrency: 3);

        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            $prefix = request()->route()?->getPrefix();

            if (str_starts_with($prefix, 'api/v1')) {
                $namespace = 'App\\Policies\\V1\\';
            } else {
                $namespace = 'App\\Policies\\';
            }

            return $namespace.class_basename($modelClass).'Policy';

        });

        Inertia::share([
            // Add this
            'appUrl' => config('app.url'),
            'lang' => fn () => json_decode(file_get_contents(
                lang_path(app()->getLocale().'.json')
            ), true),
            'locale' => app()->getLocale(),
        ]);

        Gate::define('viewApiDocs', function (User $user) {
            return in_array($user->email, ['super_admin@gmail.com']);
        });

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
    }
}
