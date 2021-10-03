<?php

namespace RhysLees\LaravelTrello;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-trello.php' => config_path('laravel-trello.php'),
        ]);

        \Illuminate\Support\Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }
                return $value;
            });
        });
    }


    public function register()
    {
        $this->app->singleton(TrelloCard::class, function(){
            return new TrelloCard();
        });
        $this->app->singleton(TrelloList::class, function(){
            return new TrelloList();
        });
    }

}
