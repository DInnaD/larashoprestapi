<?php

namespace App\Providers;

use Auth;
use App\User;
use App\Book;
use App\Magazine;
use App\Purchase;
use App\Order;
use App\Genre;
use App\Policies\UserPolicy;
use App\Policies\BookPolicy;
use App\Policies\MagazinePolicy;
use App\Policies\PurchasePolicy;
use App\Policies\OrderPolicy;
use App\Policies\GenrePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
   protected $policies = [
        User::class => UserPolicy::class,
        Book::class => BookPolicy::class,
        Magazine::class => MagazeinePolicy::class,
        Purchase::class => PurchasePolicy::class,
        Order::class => OrderPolicy::class,
        Genre::class => GenrePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('update-post', function($user, Book $book){

            return $user->hasAccess(['update-post']) or $user->id == $book->user_id;
        });

        Gate::define('update-post', function($user, Magazin $magazin){

            return $user->hasAccess(['update-post']) or $user->id == $magazine->user_id;
        });

        Gate::define('update-post', function($user, Profile $profile){

            return $user->hasAccess(['update-post']) or $user->id == $profile->user_id;
        });

        Gate::define('update-post', function($user, Purchase $purchase){

            return $user->hasAccess(['update-post']) or $user->id == $purchase->user_id;
        });
//is it true?
        Gate::define('update-post', function(User $user){

            return $user->hasAccess(['update-post']) or $user->id == true;
        });
         Gate::define('update-post', function($user, Genre $genre){

            return $user->hasAccess(['update-post']) or $user->id == $genre->user_id;
        });

    }
}
