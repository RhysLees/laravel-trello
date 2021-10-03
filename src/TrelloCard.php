<?php

namespace RhysLees\LaravelTrello;

use DateTime;
use Illuminate\Support\Facades\Http;
use RhysLees\LaravelTrello\Exceptions\CouldNotSendToTrello;

class TrelloCard
{
        /** @var string|null */
        protected $id;

        /** @var string|null */
        protected $idBoard;

        /** @var string|null */
        protected $idList;

        /** @var string */
        protected $name;

        /** @var string */
        protected $description;

        /** @var string|int */
        protected $position;

        /** @var string|null */
        protected $due;

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
         * Set the list id.
         *
         * @param $idList
         *
         * @return $this
         */
        public function idList($idList)
        {
            $this->idList = $idList;

            return $this;
        }

        /**
         * Set the card name.
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
         * Set the card description.
         *
         * @param $description
         *
         * @return $this
         */
        public function description($description)
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Set the card position.
         *
         * @param string|int $position
         *
         * @return $this
         */
        public function position($position)
        {
            $this->position = $position;

            return $this;
        }

        /**
         * Set the card position to 'top'.
         *
         * @return $this
         */
        public function top()
        {
            $this->position = 'top';

            return $this;
        }

        /**
         * Set the card position to 'bottom'.
         *
         * @return $this
         */
        public function bottom()
        {
            $this->position = 'bottom';

            return $this;
        }

        /**
         * Set the card position due date.
         *
         * @param string|DateTime $due
         *
         * @return $this
         */
        public function due($due)
        {
            if (! $due instanceof DateTime) {
                $due = new DateTime($due);
            }

            $this->due = $due->format(DateTime::ATOM);

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
                'idList' => $this->idList,
                'name' => $this->name,
                'desc' => $this->description,
                'pos' => $this->position,
                'due' => $this->due,
            ];
        }


        /**
         * Create the trello card
         *
         * @param string $idList
         *
         * @return $this
        */
        public function create()
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/cards/' . $creds;

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

            $this->idBoard = $res['idBoard'];
            $this->idList = $res['idList'];
            $this->id = $res['id'];

            return $this;
        }

        /**
         * Update the trello card
         *
         * @param string $idList
         *
         * @return $this
        */
        public function update()
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/cards/' . $creds;

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

            $this->idBoard = $res['idBoard'];
            $this->idList = $res['idList'];
            $this->id = $res['id'];

            return $this;
        }

        /**
         * Delete the trello card
         *
         * @return string
        */
        public function delete()
        {
            //Set credentials
            $creds = '?key=' . config('laravel-trello.auth.key') . '&token=' . config('laravel-trello.auth.token');

            $url = 'https://api.trello.com/1/cards/' . $this->id . $creds;

            //Get Lists or List
            $response = Http::withOptions([
                'verify' => false
            ])->delete($url);

            if ($response->failed()) {
                throw CouldNotSendToTrello::serviceRespondedWithAnError($response);
            }

            //Collect response
            $res = json_decode($response->body(), TRUE);

            return 'deleted';
        }
}
