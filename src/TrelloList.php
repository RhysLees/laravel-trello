<?php

namespace RhysLees\LaravelTrello;

use Illuminate\Support\Facades\Http;
use RhysLees\LaravelTrello\Exceptions\CouldNotSendToTrello;

class TrelloList
{
        /** @var string */
        protected $id;

        /** @var string|null */
        protected $idBoard;

        /** @var string */
        protected $name;

        /**
         * Set the card id.
         *
         * @param $id
         *
         * @return $this
         */
        public function id($id)
        {
            $this->id = $id;

            return $this;
        }

        /**
         * Set the board id.
         *
         * @param $idBoard
         *
         * @return $this
         */
        public function idBoard($idBoard)
        {
            $this->idBoard = $idBoard;

            return $this;
        }

        /**
         * Set the list name.
         *
         * @param $name
         *
         * @return $this
         */
        public function name($name)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * @return array
         */
        public function toArray()
        {
            return [
                'id' => $this->id,
                'idBoard' => $this->idBoard,
                'name' => $this->name,
            ];
        }

        /**
         * Create the trello list
         *
         * @return $this
        */
        public function create()
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/lists/' . $creds;

            $query = $this->toArray();
            $query['idBoard'] = $this->idBoard;

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->post($url, $query);

            if ($response->failed()) {
                throw CouldNotSendToTrello::serviceRespondedWithAnError($response);
            }

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->id = $res['id'];
            $this->idBoard = $res['idBoard'];

            return $this;
        }

        /**
         * Update the trello list
         *
         * @param string $idBoard
         *
         * @return $this
        */
        public function update($idBoard)
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/lists/' . $this->idList . $creds;

            $query = $this->toArray();
            $query['idBoard'] = $idBoard;

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->put($url, $query);

            if ($response->failed()) {
                throw CouldNotSendToTrello::serviceRespondedWithAnError($response);
            }

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->idBoard = $res['idBoard'];

            return $this;
        }

        /**
         * Delete the trello list
         *
         * @return string
        */
        public function delete()
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/lists/' . $this->id . '/closed' . $creds;

            $query = [
                'value' => true
            ];

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->put($url, $query);

            if ($response->failed()) {
                throw CouldNotSendToTrello::serviceRespondedWithAnError($response);
            }

            //Collect response
            $res = json_decode($response->body(), TRUE);

            return 'deleted';
        }
}
