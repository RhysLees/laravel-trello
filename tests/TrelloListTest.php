<?php

namespace RhysLees\LaravelTrello\Test;

use DateTime;
use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use RhysLees\LaravelTrello\TrelloList;

class TrelloListTest extends TestCase
{
    /** @var \RhysLees\LaravelTrello\TrelloList */
    protected $trellolist;

    public function setUp(): void
    {
        parent::setUp();

        $this->trellolist = new TrelloList();
    }

     /** @test */
     public function it_can_set_the_id_board()
     {
         $this->trellolist->idBoard('6159d7538f756f0a2287ab2b');

         $this->assertEquals('6159d7538f756f0a2287ab2b', Arr::get( $this->trellolist->toArray(), 'idBoard'));
     }

    /** @test */
    public function it_can_set_the_name()
    {
        $this->trellolist->name('ListName');

        $this->assertEquals('ListName', Arr::get( $this->trellolist->toArray(), 'name'));
    }

    /** @test */
    public function it_can_be_sent_to_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token',  '');

        $this->trellolist
            ->name('ListName')
            ->idBoard('')
            ->create();

            $this->assertNotNull(Arr::get( $this->trellolist->toArray(), 'idBoard'));
    }


    /** @test */
    public function it_can_be_deleted_on_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token', '');

        $list =  new TrelloList;

        $list->name('ListName')
            ->idBoard('')
            ->create();

        $deleted = $list->delete();

        $this->assertEquals('deleted',$deleted);
    }
}
