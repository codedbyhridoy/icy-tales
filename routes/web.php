<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsletterController;

use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('TempWelcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


/*
|--------------------------------------------------------------------------
| Frontend
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/about', [AboutController::class, 'index'])
    ->name('about.index');

Route::get('/faqs', [FaqsController::class, 'index'])
    ->name('faqs.index');

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/products', [HomeController::class, 'products'])
    ->name('explore.products');

Route::get('/categories/{category:slug}', [ProductController::class, 'index'])
    ->name('frontend.categories.show');

Route::get('/explore-products/{product}', [ProductController::class, 'show'])
    ->name('explore-products.show');

/*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
*/

Route::get('/blogs', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])
    ->name('blog.show');

   /*
|--------------------------------------------------------------------------
| Blog Comments
|--------------------------------------------------------------------------
*/ 

Route::prefix('blogs/{blog:slug}/comments')
    ->name('blog.comments.')
    ->group(function () {

        Route::post('/', [CommentController::class, 'store'])
            ->name('store');

        Route::post('/{comment}', [CommentController::class, 'update'])
            ->name('update');

        Route::delete('/{comment}', [CommentController::class, 'destroy'])
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Team Members
|--------------------------------------------------------------------------
*/

Route::get('/team-members', [MemberController::class, 'teamMembers'])
    ->name('team-members');

Route::get('/team-members/load-more', [MemberController::class, 'loadMore'])
    ->name('team-members.load-more');

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout/order', [CheckoutController::class, 'store'])
    ->name('checkout.store');

/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/

Route::prefix('cart')
    ->name('cart.')
    ->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'store'])->name('store');
        Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    });

/*
|--------------------------------------------------------------------------
| Reviews
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Payment
|--------------------------------------------------------------------------
*/

Route::get('/payment/sslcommerz/{order}', [PaymentController::class, 'sslcommerz'])
    ->name('payment.sslcommerz');

Route::post('/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::post('/payment/fail', [PaymentController::class, 'fail'])
    ->name('payment.fail');

Route::post('/payment/cancel', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');

Route::post('/payment/ipn', [PaymentController::class, 'ipn'])
    ->name('payment.ipn');

/*
|--------------------------------------------------------------------------
| Locations
|--------------------------------------------------------------------------
*/

Route::get('/states', [FrontendController::class, 'states'])
    ->name('states');

Route::get('/cities', [FrontendController::class, 'cities'])
    ->name('cities');

/*
|--------------------------------------------------------------------------
| Policies
|--------------------------------------------------------------------------
*/

Route::get('/privacy-policy', function () {
    return Inertia::render('Frontend/Policies/PrivacyPolicy');
})->name('privacy-policy');

Route::get('/terms-conditions', function () {
    return Inertia::render('Frontend/Policies/TermsAndConditions');
})->name('terms-conditions');

/*
|--------------------------------------------------------------------------
| Status Pages
|--------------------------------------------------------------------------
*/

Route::prefix('status')
    ->name('status.')
    ->group(function () {

        Route::get('/order-success', function () {
            return Inertia::render('Frontend/Status/Success');
        })->name('order.success');

        Route::get('/page-not-found', function () {
            return Inertia::render('Frontend/Status/NotFound');
        })->name('not-found');

        Route::get('/coming-soon', function () {
            return Inertia::render('Frontend/Status/ComingSoon');
        })->name('coming-soon');
    });

/*
|--------------------------------------------------------------------------
| Temporary Pages
|--------------------------------------------------------------------------
*/


Route::get('/special-offers', function () {
    return Inertia::render('Frontend/SpecialOfferPage');
})->name('special-offers');

Route::get('/review', function () {
    return Inertia::render('Frontend/ReviewPage');
})->name('review');

/*
|--------------------------------------------------------------------------
| NewsLetter
|--------------------------------------------------------------------------
*/

Route::prefix('newsletter')
    ->name('newsletter.')
    ->group(function () {
        Route::post('/', [NewsletterController::class, 'store'])
            ->name('store');

        Route::delete('/{newsletter}', [NewsletterController::class, 'destroy'])
            ->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Admin Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });



require __DIR__ . '/auth.php';
require __DIR__ . '/super_admin.php';
