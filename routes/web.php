<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
// Route::get('/check-imagick', function () {
//     if (extension_loaded('imagick')) {
//         return "Imagick is installed.";
//     } else {
//         return "Imagick is not installed.";
//     }
// });

//
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'productDetails'])->name('product.details');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact-store', [ContactController::class, 'contactStore'])->name('contact.store');
Route::get('/about-us', [HomeController::class, 'about'])->name('about');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

//wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist-store', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
Route::get('/wishlist-to-cart/{rowId}', [WishlistController::class, 'moveToCart'])->name('wishlist.ToCart');
Route::get('/wishlist/remove-item/{rowId}', [WishlistController::class, 'removeFromWhislist'])->name('wishlist.remove');

//cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/update/qty-increase/{rowId}', [CartController::class, 'qtyIncrease'])->name('cartQty.increase');
Route::put('/cart/update/qty-decrease/{rowId}', [CartController::class, 'qtyDecrease'])->name('cartQty.decrease');
Route::delete('/cart/remove-item/{rowId}', [CartController::class, 'removeCartItem'])->name('cartItem.remove');

//Coupon
Route::post('/coupon/apply-coupon', [CouponController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/coupon/remove-coupon', [CouponController::class, 'removeCoupon'])->name('coupon.remove');

Route::middleware(['auth'])->group(function () {
    Route::get('/customer-dashboard', [UserController::class, 'index'])->name('user.index');

    //checkout
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/checkout-store', [CheckoutController::class, 'storeCheckout'])->name('checkout.store');

    //Order Confirm Page
    Route::get('/order/confirm', [OrderController::class, 'orderConfirmation'])->name('order.confirm');

    //customer account
    Route::get('/user/orders', [OrderController::class, 'userOrders'])->name('user.orders');
    Route::get('/user/order-details/{orderId}', [OrderController::class, 'userOrderDetails'])->name('user.order-details');
    Route::post('/user/cancel-order', [OrderController::class, 'userCancelOrder'])->name('user.cancel-order');
    Route::get('/user/account-setting', [UserController::class, 'customerAccountSetting'])->name('customer.account');
    Route::put('/user/account-update', [UserController::class, 'customerAccountUpdate'])->name('customer.update');
    Route::get('/user/address', [UserController::class, 'customerAddress'])->name('customer.address');
    Route::get('/user/new-address', [UserController::class, 'createNewAddress'])->name('create.address');
    Route::post('/user/address-store', [UserController::class, 'storeAddress'])->name('store.address');
    Route::get('/user/address/{id}/edit', [UserController::class, 'editAddress'])->name('edit.address');
    Route::put('/user/address/update', [UserController::class, 'updateAddress'])->name('update.address');
    Route::get('/user/wishlists', [UserController::class, 'customerWishlist'])->name('customer.wishlist');
});

Route::middleware(['auth', 'admin-role'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.index');
    //Brand
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('brand.index');
    Route::get('/admin/create-brand', [AdminController::class, 'create'])->name('brand.create');
    Route::post('/admin/create-brand', [AdminController::class, 'store'])->name('brand.store');
    Route::get('/admin/brand/edit/{brand}', [AdminController::class, 'editBrand'])->name('brand.edit');
    Route::put('/admin/brand/update', [AdminController::class, 'updateBrand'])->name('brand.update');
    Route::delete('/admin/brand/delete', [AdminController::class, 'brandDestroy'])->name('brand.delete');

    //Category
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/admin/create-category', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/admin/create-category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/admin/category/edit/{category}', [CategoryController::class, 'editCategory'])->name('category.edit');
    Route::put('/admin/category/update', [CategoryController::class, 'updateCategory'])->name('category.update');
    Route::delete('/admin/category/delete', [CategoryController::class, 'categoryDestroy'])->name('category.delete');

    //products
    Route::get('/admin/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/admin/create-product', [ProductController::class, 'create'])->name('product.create');
    Route::post('/admin/create-product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/admin/product/edit/{product}', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::put('/admin/product/update', [ProductController::class, 'updateProduct'])->name('product.update');
    Route::delete('/admin/product/delete', [ProductController::class, 'deleteProduct'])->name('product.delete');

    //coupons
    Route::get('/admin/coupons', [CouponController::class, 'coupons'])->name('coupon.index');
    Route::get('/admin/create-coupons', [CouponController::class, 'createCoupon'])->name('coupon.create');
    Route::post('/admin/create-coupons', [CouponController::class, 'storeCoupon'])->name('coupon.store');
    Route::get('/admin/coupon/edit/{id}', [CouponController::class, 'editCoupon'])->name('coupon.edit');
    Route::put('/admin/coupon/edit', [CouponController::class, 'update'])->name('coupon.update');
    Route::delete('/admin/coupon/delete', [CouponController::class, 'destroy'])->name('coupon.delete');

    //contact
    Route::get('admin/messages', [ContactController::class, 'index'])->name('contact.index');
    Route::delete('admin/message-delete', [ContactController::class, 'deleteContact'])->name('contact.delete');

    //users
    Route::get('/users', [UserController::class, 'indexAdmin'])->name('admin.user.index');

    //settings
    Route::get('/user/settings', [UserController::class, 'adminSetting'])->name('admin.setting');
    Route::put('/admin/update-setting', [UserController::class, 'updateSetting'])->name('update.setting');

    //orders
    Route::get('/admin/orders', [OrderController::class, 'ordersAdminPanel'])->name('admin.orders');
    Route::get('/admin/orderd-items/{orderId}', [OrderController::class, 'orderdItemsAdminPanel'])->name('order.details');
    Route::put('/admin/update/order-status', [OrderController::class, 'updateAdminOrderStatus'])->name('update.order-status');

    //Slides
    Route::get('/admin/sliders', [SliderController::class, 'Slider'])->name('admin.slide');
    Route::get('/admin/slider/create', [SliderController::class, 'sliderCreate'])->name('slide.create');
    Route::post('/admin/slider/store', [SliderController::class, 'sliderStore'])->name('slide.store');
    Route::get('/admin/slider/edit/{id}', [SliderController::class, 'sliderEdit'])->name('slide.edit');
    Route::put('/admin/slider/update', [SliderController::class, 'sliderUpdate'])->name('slide.update');
    Route::delete('/admin/slider/delete', [SliderController::class, 'deleteSlider'])->name('slide.delete');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
