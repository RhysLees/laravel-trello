<?php

namespace RhysLees\LaravelTrello\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RhysLees\LaravelTrello\Exceptions\GetTrelloListException;
use RhysLees\LaravelTrello\Exceptions\GetTrelloListsException;
use RhysLees\LaravelTrello\Exceptions\PostTrelloListException;
use RhysLees\LaravelTrello\Exceptions\CreateTrelloListException;

trait TrelloBoards
{

    public function getLists()
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.trello.com/1/boards/' . config('laravel-trello.board.id') . '/lists/' . $creds)->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->lists = Collection::unwrap(collect($res));

            return $this;

        } catch (\Throwable $th) {
            throw new GetTrelloListsException($th->getMessage(), $th->getCode());
        }
    }

    public function createList(array $list)
    {
        if(!array_key_exists('name', $list)){
            throw new CreateTrelloListException('key \'name\' not in array', 400);
        }

        $data = collect($list)->only(['name', 'idListSource', 'pos']);

        // dd($data->toArray());

        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->post('https://api.trello.com/1/boards/' . config('laravel-trello.board.id') . '/lists/' . $creds, $data->toArray())->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->list = Collection::unwrap(collect($res));

            return $this;

        } catch (\Throwable $th) {
            throw new PostTrelloListException($th->getMessage(), $th->getCode());
        }
    }
}
