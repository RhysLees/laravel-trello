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
