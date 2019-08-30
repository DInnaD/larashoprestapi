<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->bind( Generator::class, function ( $app ) {

            $faker = \Faker\Factory::create();
            $faker->addProvider( new CustomFakerProvider( $faker ) );

            return $faker;
        } );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
