<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
// use Spatie\Permission\Models\Permission;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $isAdminRequest = $request->is('admin*') || $request->routeIs('admin.*');

        $webUser   = auth()->guard('web')->user();
        $adminUser = auth()->guard('admin')->user();

        $user = $isAdminRequest ? $adminUser : $webUser;
        $guard = $isAdminRequest ? 'admin' : 'web';

        $data = [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'guard' => $user ? $guard : null,
                'is_admin_login' => $adminUser ? true: false,
                'role' => $user?->getRoleNames()?->first(),
                'can' => $user
                    ? $user->getAllPermissions()
                        ->mapWithKeys(fn ($p) => [$p->name => true])
                        ->all()
                    : [],
            ],
            'cart' => fn () => (new \App\Services\CartService())->summary(),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];

        return $data;
    }
}
