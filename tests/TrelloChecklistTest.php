<?php

namespace RhysLees\LaravelTrello\Test;

use DateTime;
use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use RhysLees\LaravelTrello\TrelloChecklist;

class TrelloChecklistTest extends TestCase
{
    /** @var \RhysLees\LaravelTrello\TrelloChecklist */
    protected $trellochecklist;

    public function setUp(): void
    {
        parent::setUp();

        $this->trellochecklist = new TrelloChecklist();
    }

     /** @test */
     public function it_can_set_the_id_card()
     {
         $this->trellochecklist->idCard('');

         $this->assertEquals('', Arr::get( $this->trellochecklist->toArray(), 'idCard'));
     }

    /** @test */
    public function it_can_set_the_name()
    {
        $this->trellochecklist->name('ChecklistName');

        $this->assertEquals('ChecklistName', Arr::get( $this->trellochecklist->toArray(), 'name'));
    }

    /** @test */
    public function it_can_be_sent_to_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token',  '');

        $this->trellochecklist
            ->name('ChecklistName')
            ->idCard('')
            ->create();

            $this->assertNotNull(Arr::get( $this->trellochecklist->toArray(), 'idCard'));
    }


    /** @test */
    public function it_can_be_deleted_on_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token', '');

        $list =  new trellochecklist;

        $list->name('ChecklistName')
            ->idCard('')
            ->create();

        $deleted = $list->delete();

        $this->assertEquals('deleted',$deleted);
    }
}
