<?php

use App\Http\Controllers\SuperAdmin\InquiryController;
use App\Http\Controllers\RolePermission\PermissionController;
use App\Http\Controllers\RolePermission\RoleController;
use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\ProductController;
use App\Http\Controllers\SuperAdmin\Posts\PostController;
use App\Http\Controllers\SuperAdmin\Posts\TagController;
use App\Http\Controllers\SuperAdmin\OrderController;
use App\Http\Controllers\SuperAdmin\MemberController;
use App\Http\Controllers\SuperAdmin\StatisticController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\Posts\BlogCategoryController;
use App\Http\Controllers\SuperAdmin\ReviewController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
|
| Routes related to the super admin
|
*/

Route::middleware(['auth:admin', 'role:super admin|admin'])->name('super_admin.')->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // role
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/assign-permission/{id}', [RoleController::class, 'edit'])->name('role.assign-permission');
    Route::patch('/roles/assign-permission/{id}', [RoleController::class, 'update'])->name('role.assign-permission-update');
    // permission
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/assign-role/{id}', [PermissionController::class, 'edit'])->name('permission.assign-role');
    Route::patch('/permissions/assign-role/{id}', [PermissionController::class, 'update'])->name('permission.assign-role-update');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');

    // Categoies
    Route::resource('categories', CategoryController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Product Reviews
    Route::prefix('reviews')->name('reviews.')->controller(ReviewController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/{review}', 'show')->name('show');

            Route::patch('/{review}/approve', 'approve')->name('approve');

            Route::patch('/{review}/reject', 'reject')->name('reject');

            Route::delete('/{review}', 'destroy')->name('destroy');
        });

    // Posts
    Route::resource('blogs', PostController::class);
    Route::resource('blog-categories', BlogCategoryController::class);

    Route::resource('tags', TagController::class)->except(['show']);

    // Orders
    Route::resource('orders', OrderController::class);

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status');

    // Team Members
    Route::resource('team-members', MemberController::class);

    // Statistics
    Route::resource('statistics', StatisticController::class);


    // setting
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
