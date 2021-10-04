<?php

namespace RhysLees\LaravelTrello;

use Illuminate\Support\Facades\Http;
use RhysLees\LaravelTrello\Exceptions\CouldNotSendToTrello;

class TrelloChecklist
{
        /** @var string */
        protected $id;

        /** @var string|null */
        protected $idCard;

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
         * Set the card id.
         *
         * @param $idCard
         *
         * @return $this
         */
        public function idCard($idCard)
        {
            $this->idCard = $idCard;

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
                'idCard' => $this->idCard,
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

            $url = 'https://api.trello.com/1/checklists/' . $creds;

            $query = $this->toArray();

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
            $this->idCard = $res['idCard'];

            return $this;
        }

        /**
         * Update the trello list
         *
         * @param string $idCard
         *
         * @return $this
        */
        public function update()
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/lists/' . $this->idList . $creds;

            $query = $this->toArray();

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->put($url, $query);

            if ($response->failed()) {
                throw CouldNotSendToTrello::serviceRespondedWithAnError($response);
            }

            //Collect response
            $res = json_decode($response->body(), TRUE);

            $this->idCard = $res['idCard'];

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
