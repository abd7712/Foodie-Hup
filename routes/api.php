<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\SuperAdminAndAdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Route;

/****auth*****/
Route::post("/register",[AuthController::class,"register"]);
Route::post("/login",[AuthController::class,"login"]);
Route::post("/logout",[AuthController::class,"logout"]);
Route::get("/email/verify/{user_id}/{hash}",[AuthController::class,"verify"]);

/****super admin*****/
Route::post("/a/create/admin",[SuperAdminController::class,"create_admin"])->middleware(["CheckJWTToken","IsSuperAdmin","SetOnline"]);
Route::post("/a/create/chef",[SuperAdminController::class,"create_chef"])->middleware(["CheckJWTToken","IsSuperAdmin","SetOnline"]);
Route::post("/a/create/waiter",[SuperAdminController::class,"create_waiter"])->middleware(["CheckJWTToken","IsSuperAdmin","SetOnline"]);
Route::get("/a/show/admins",[SuperAdminController::class,"show_admins"])->middleware(["CheckJWTToken","IsSuperAdmin","SetOnline"]);
Route::get("/a/show/chefs",[SuperAdminController::class,"show_chefs"])->middleware(["CheckJWTToken","IsSuperAdmin","SetOnline"]);
Route::get("/a/show/waiters",[SuperAdminController::class,"show_waiters"])->middleware(["CheckJWTToken","IsSuperAdmin","SetOnline"]);

/****super admin or admin*****/
Route::post("/a/create/table",[SuperAdminAndAdminController::class,"create_table"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/tables",[SuperAdminAndAdminController::class,"show_tables"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/create/category",[SuperAdminAndAdminController::class,"create_category"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/categories",[SuperAdminAndAdminController::class,"show_categories"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/create/product/{category_id}",[SuperAdminAndAdminController::class,"create_product"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/products/{category_id}",[SuperAdminAndAdminController::class,"show_products"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/add/package",[SuperAdminAndAdminController::class,"add_package"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/add/product/packages/{package_id}",[SuperAdminAndAdminController::class,"add_product_packages"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/packages",[SuperAdminAndAdminController::class,"show_packages"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/products/package/{package_id}",[SuperAdminAndAdminController::class,"show_products_package"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/pending/books",[SuperAdminAndAdminController::class,"show_pending_books"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/tables/available/{book_id}",[SuperAdminAndAdminController::class,"show_tables_available_for_book"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/confirm/book/{book_id}/{table_id}",[SuperAdminAndAdminController::class,"confirm_book"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/rejected/book/{book_id}",[SuperAdminAndAdminController::class,"rejected_book"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/show/confirming/books",[SuperAdminAndAdminController::class,"show_confirming_books"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::post("/a/arrive/book/{book_id}",[SuperAdminAndAdminController::class,"arrive_book"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);
Route::get("/a/get/rate",[SuperAdminAndAdminController::class,"get_rate"])->middleware(["CheckJWTToken","IsSuperAdminOrAdmin","SetOnline"]);

/****user*****/
Route::get("/show",[UserController::class,"show_n_p_r_c"]);
Route::get("/show/products/{category_id}",[UserController::class,"show_products"]);
Route::post("/u/make/book",[UserController::class,"book"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","SetOnline"]);
Route::get("/u/show/my/books",[UserController::class,"show_my_books"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","SetOnline"]);
Route::get("/u/show/{book_id}",[UserController::class,"show_n_p_r_c_for_arrived_book"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::get("/u/show/products/{book_id}/{category_id}",[UserController::class,"show_products_for_arrived_book"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::post("/u/add/product/to/cart/{product_id}/{book_id}",[UserController::class,"add_to_cart"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::get("/u/show/my/carts/{book_id}",[UserController::class,"show_my_carts"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::post("/u/order/my/cart/{book_id}",[UserController::class,"order"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::get("/u/show/my/orders/{book_id}",[UserController::class,"show_my_orders"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::post("/u/request/invoice/{book_id}",[UserController::class,"request_invoice"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","EnsureUserOwnsBooking","SetOnline"]);
Route::post("/u/rate/{book_id}",[UserController::class,"rate"])->middleware(["CheckJWTToken","CheckNoBadGuys","IsUser","SetOnline"]);

/****chef*****/
Route::get("/c/show/order/items",[ChefController::class,"show_order_items"])->middleware(["CheckJWTToken","IsChef","SetOnline"]);
Route::post("/c/progress/order/item/{order_item_id}",[ChefController::class,"progress_order_item"])->middleware(["CheckJWTToken","IsChef","SetOnline"]);
Route::post("/c/ready/order/item/{order_item_id}",[ChefController::class,"ready_order_item"])->middleware(["CheckJWTToken","IsChef","SetOnline"]);

/****waiter*****/
Route::get("/w/show/order/items/ready",[WaiterController::class,"show_order_items_ready"])->middleware(["CheckJWTToken","IsWaiter","SetOnline"]);
Route::post("/w/served/{order_item_id}",[WaiterController::class,"served"])->middleware(["CheckJWTToken","IsWaiter","SetOnline"]);
