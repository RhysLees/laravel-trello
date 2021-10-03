<?php

namespace RhysLees\LaravelTrello\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RhysLees\LaravelTrello\Exceptions\GetTrelloListException;
use RhysLees\LaravelTrello\Exceptions\GetTrelloListsException;
use RhysLees\LaravelTrello\Exceptions\PostTrelloListException;
use RhysLees\LaravelTrello\Exceptions\CreateTrelloListException;
use RhysLees\LaravelTrello\Exceptions\UpdateTrelloListException;

trait TrelloLists
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

    public function getList(string $id)
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.trello.com/1/lists/' . $id . $creds)->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->list = Collection::unwrap(collect($res));

            return $this;

        } catch (\Throwable $th) {
            throw new GetTrelloListException($th->getMessage(), $th->getCode());
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
            throw new CreateTrelloListException($th->getMessage(), $th->getCode());
        }
    }

    public function updateList(array $values)
    {
        if(!array_key_exists('id', $values)){
            throw new UpdateTrelloListException('key \'id\' not in array', 400);
        }

        $data = collect($values)->only(['id', 'name', 'closed', 'idBoard', 'pos', 'subscribed']);

        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->put('https://api.trello.com/1/lists/' . $data['id'] . $creds, $data->toArray())->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->list = Collection::unwrap(collect($res));

            return $this;

        } catch (\Throwable $th) {
            throw new UpdateTrelloListException($th->getMessage(), $th->getCode());
        }
    }

    public static function archiveList(string $id, bool $archived)
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->put('https://api.trello.com/1/lists/' . $id . '/closed/' . $creds, [
                'value' => $archived
            ])->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            return collect($res);

        } catch (\Throwable $th) {
            throw new UpdateTrelloListException($th->getMessage(), $th->getCode());
        }
    }

    public function getCardsOnList(string $id)
    {
        try {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.trello.com/1/lists/' . $id . '/cards/' . $creds)->throw();

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->cards = Collection::unwrap(collect($res));

            return $this;

        } catch (\Throwable $th) {
            throw new GetTrelloListsException($th->getMessage(), $th->getCode());
        }
    }
}
