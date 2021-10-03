<?php

namespace RhysLees\LaravelTrello\Exceptions;

class CouldNotSendToTrello extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static('Trello responded with an error: `'.$response->getBody()->getContents().'`');
    }
}
