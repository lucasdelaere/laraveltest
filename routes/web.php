<?php

use App\Http\Controllers\AdminUsersController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* frontend */
Route::get("/", function () {
    return view("welcome");
});
Route::get("contactformulier", [
    \App\Http\Controllers\ContactController::class,
    "create",
])->name("contact.create");
Route::post("contactformulier", [
    \App\Http\Controllers\ContactController::class,
    "store",
]);

/*backend*/

Route::group(
    ["prefix" => "admin", "middleware" => ["auth", "verified"]],
    function () {
        Route::resource(
            "posts",
            \App\Http\Controllers\AdminPostsController::class,
            ["except" => ["show"]]
        );
        Route::get("posts/{post:slug}", [
            \App\Http\Controllers\AdminPostsController::class,
            "show",
        ])->name("posts.show");
        Route::resource(
            "categories",
            \App\Http\Controllers\AdminCategoriesController::class
        );
        Route::post("restore/{category}", [
            \App\Http\Controllers\AdminCategoriesController::class,
            "restore",
        ])->name("categories.restore");
        //see Kernel.php
        Route::get("/", [
            App\Http\Controllers\HomeController::class,
            "index",
        ])->name("home");
        Route::get("authors/{author:name}", [
            \App\Http\Controllers\AdminPostsController::class,
            "indexByAuthor",
        ])->name("authors");
        Route::post("posts/restore/{post}", [
            \App\Http\Controllers\AdminPostsController::class,
            "postRestore",
        ])->name("admin.postrestore");
        Route::group(["middleware" => "admin"], function () {
            Route::resource("users", AdminUsersController::class);
            Route::post("users/restore/{user}", [
                AdminUsersController::class,
                "restore",
            ])->name("admin.restore");
            Route::get("usersblade", [
                AdminUsersController::class,
                "index2",
            ])->name("users.index2");
        });
    }
);

Auth::routes(["verify" => true]); //variable named verify.
