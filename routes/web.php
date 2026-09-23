<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\mailController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminTechController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TechProfileController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ResidentProfileController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/', [HomeController::class, 'public'])->name('public');
Route::post('/', [HomeController::class, 'store'])->name('public.store');

Auth::routes(['verify' => true]);
// Auth::routes();

Route::get('/home', [App\Http\Controllers\IndexController::class, 'index'])->name('home')->middleware('verified');

// 'verified' is enforced here because User implements MustVerifyEmail and
// Auth::routes(['verify' => true]) is enabled - previously only /home checked it,
// so every portal was reachable without confirming the address.
Route::group(['middleware' => ['admin', 'verified']], function () {
    Route::get('/adminpanel', [HomeController::class, 'home'])->name('adminpanel');
    // Route::get('/adminpanel', [HomeController::class, 'getTotalServices'])->name('adminpanel');
    Route::get('msgs', [HomeController::class, 'read'])->name('msgs');
    Route::delete('msgs/{id}', [HomeController::class, 'delete'])->name('msgs.delete');
    Route::get('billing', function () {
        return view('billing');
    })->name('billing');
    Route::get('profile', function () {
        return view('profile');
    })->name('profile');
    Route::get('rtl', function () {
        return view('rtl');
    })->name('rtl');
    Route::get('tables', function () {
        return view('tables');
    })->name('tables');
    Route::get('virtual-reality', function () {
        return view('virtual-reality');
    })->name('virtual-reality');
    Route::get('static-sign-in', function () {
        return view('static-sign-in');
    })->name('sign-in');
    Route::get('static-sign-up', function () {
        return view('static-sign-up');
    })->name('sign-up');
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('adminpanel.categories');
        Route::post('/', [CategoryController::class, 'store'])->name('adminpanel.category.store');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('adminpanel.category.destroy');
    });

    Route::group(['prefix' => 'availability'], function () {
        Route::get('/', [AvailabilityController::class, 'index'])->name('adminpanel.availability');
        Route::post('/', [AvailabilityController::class, 'store'])->name('adminpanel.availability.store');
        Route::delete('/{id}', [AvailabilityController::class, 'destroy'])->name('adminpanel.availability.destroy');
    });

    Route::group(['prefix' => 'technicians'], function () {
        Route::get('/', [AdminTechController::class, 'index'])->name('adminpanel.technicians');
        Route::get('/create', [AdminTechController::class, 'create'])->name('adminpanel.technicians.create');
        Route::post('/create', [AdminTechController::class, 'store'])->name('adminpanel.technicians.store');
        Route::get('/{id}', [AdminTechController::class, 'edit'])->name('adminpanel.technicians.edit');
        Route::put('/{id}', [AdminTechController::class, 'update'])->name('adminpanel.technicians.edit');
        Route::delete('/{id}', [AdminTechController::class, 'destroy'])->name('adminpanel.technicians.destroy');
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders');
        Route::get('/{id}', [OrderController::class, 'view'])->name('orders.view');
        Route::put('/{id}', [OrderController::class, 'updateStatus'])->name('orders.view');
    });

    // User management - admins create accounts, invitees set their own password.
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('adminpanel.users.index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('adminpanel.users.create');
        Route::post('/', [AdminUserController::class, 'store'])->name('adminpanel.users.store');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('adminpanel.users.edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('adminpanel.users.update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('adminpanel.users.destroy');
        Route::post('/{user}/resend-invitation', [AdminUserController::class, 'resendInvitation'])->name('adminpanel.users.resend');
    });

    Route::get('mail/contact', [mailController::class, "mailform"])->name('mail.create');
    Route::post('mail/sendemail', [mailController::class, "sendmail"])->name('sendmail');
});

// Invitation flow - reachable while logged out.
Route::get('/set-password/{token}', [SetPasswordController::class, 'show'])->name('password.set');
Route::post('/set-password', [SetPasswordController::class, 'store'])->name('password.set.store');
Route::get('/login/forgot-password', [ResetController::class, 'create']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendEmail']);
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPass'])->name('password.reset');
Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

// Resident Portal

Route::group(['middleware' => ['resident', 'verified']], function () {
    Route::group(['prefix' => 'pages'], function () {
        Route::get('/home', [PagesController::class, 'home'])->name('home');
        Route::get('/cart', [PagesController::class, 'cart'])->name('cart');
        Route::get('/wishlist', [PagesController::class, 'wishlist'])->name('wishlist');
        Route::get('/account', [PagesController::class, 'account'])->name('account');
        Route::get('/checkout', [PagesController::class, 'checkout'])->name('checkout');
        Route::get('/success', [PagesController::class, 'success'])->name('success');
        Route::get('/products/{id}', [PagesController::class, 'product'])->name('product');

        Route::post('/stripe-checkout', [CheckoutController::class, 'stripeCheckout'])->name('stripeCheckout');

        Route::get('/profile', [ResidentProfileController::class, 'create'])->name('pages.profile');
        Route::post('/profile', [ResidentProfileController::class, 'store'])->name('pages.profile');

        //Cart
        Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('addToCart');
        Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('removeFromCart');
        Route::post('/add-to-wishlist/{id}', [WishlistController::class, 'post'])->name('addToWishlist');
        Route::post('/remove-from-wishlist/{id}', [WishlistController::class, 'remove'])->name('removeFromWishlist');
    });
});

// Technicina Panel
Route::group(['middleware' => ['technician', 'verified']], function () {

    Route::group(['prefix' => 'technicianpanel'], function () {
        Route::get('/', [TechnicianController::class, 'dashboard'])->name('technicianpanel');
        Route::get('/introduction', [TechnicianController::class, 'intro'])->name('technicianpanel.introduction');
        Route::get('/technician/pages/view/{id}', [TechnicianController::class, 'view'])->name('technicianpanel.pages.view');
        Route::post('/', [TechnicianController::class, 'store'])->name('technicianpanel.store');
        

        Route::get('/confirmed', [TechnicianController::class, 'confirmed'])->name('technicianpanel.confirmed');
        Route::put('/{id}', [TechnicianController::class, 'updateStatus'])->name('technicianpanel.status.update');

        Route::get('/profile', [TechProfileController::class, 'create'])->name('technicianpanel.pages.profile');
        Route::post('/profile', [TechProfileController::class, 'store'])->name('technicianpanel.pages.store');
    });
});

/*
 * These were reachable by anyone. GET /messages returned every message with the
 * full related user object attached - emails, phone numbers, role, coordinates -
 * and POST /messages called Auth::user()->messages() with no user, so it threw
 * for guests. chat.blade.php also reads Auth::user()->role unguarded.
 *
 * Chat.vue fetches /messages with axios from the browser, so the session cookie
 * is sent and the `auth` middleware is transparent to it.
 */
Route::middleware('auth')->group(function () {
    Route::get('chat', [App\Http\Controllers\HomeController::class, 'chat'])->name('chat');
    Route::get('messages', [App\Http\Controllers\HomeController::class, 'messages'])->name('messages');
    Route::post('messages', [App\Http\Controllers\HomeController::class, 'messageStore'])->name('messageStore');
});
