<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class DataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function(View $view){
            $view->with(['usersCount' => User::count(), 'postsCount' => Post::count(), 'categoriesCount' => Category::count()]);
        });
    }
}
