<?php

namespace RhysLees\LaravelTrello;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RhysLees\LaravelTrello\Exceptions\GetTrelloListsException;
use RhysLees\LaravelTrello\Traits\TrelloLists;

class LaravelTrello
{
    use TrelloLists;


    public function getMembers()
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Create the card
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.trello.com/1/boards/' . config('laravel-trello.board.id') . '/members/' . $creds)->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->members = Collection::unwrap(collect($res)->filter());

            return $this;

        } catch (\Throwable $th) {
            throw new GetTrelloListsException($th->getMessage(), $th->getCode());
        }
    }

    public function getCards()
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Create the card
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.trello.com/1/boards/' . config('laravel-trello.board.id') . '/cards/' . $creds)->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->cards = Collection::unwrap(collect($res)->filter());

            return $this;
        } catch (\Throwable $th) {
            throw new GetTrelloListsException($th->getMessage(), $th->getCode());
        }
    }

    public function getChecklists()
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Create the card
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.trello.com/1/boards/' . config('laravel-trello.board.id') . '/checklists/' . $creds)->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->checklists = Collection::unwrap(collect($res)->filter());

            return $this;
        } catch (\Throwable $th) {
            throw new GetTrelloListsException($th->getMessage(), $th->getCode());
        }
    }
}
