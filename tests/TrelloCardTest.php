<?php

namespace RhysLees\LaravelTrello\Test;

use DateTime;
use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use RhysLees\LaravelTrello\TrelloCard;

class TrelloCardTest extends TestCase
{
    protected $loadEnvironmentVariables = true;

    /** @var \RhysLees\LaravelTrello\TrelloCard */
    protected $trellocard;

    public function setUp(): void
    {
        parent::setUp();

        $this->trellocard = new TrelloCard();
    }

    /** @test */
    public function it_can_set_the_id_list()
    {
        $this->trellocard->idList('');

        $this->assertEquals('', Arr::get( $this->trellocard->toArray(), 'idList'));
    }

    /** @test */
    public function it_can_set_the_name()
    {
        $this->trellocard->name('CardName');

        $this->assertEquals('CardName', Arr::get( $this->trellocard->toArray(), 'name'));
    }

    /** @test */
    public function it_can_set_the_description()
    {
        $this->trellocard->description('MyDescription');

        $this->assertEquals('MyDescription', Arr::get( $this->trellocard->toArray(), 'desc'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_string()
    {
        $date = new DateTime('tomorrow');
        $this->trellocard->due('tomorrow');

        $this->assertEquals($date->format(DateTime::ATOM), Arr::get( $this->trellocard->toArray(), 'due'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_datetime()
    {
        $date = new DateTime('tomorrow');
        $this->trellocard->due($date);

        $this->assertEquals($date->format(DateTime::ATOM), Arr::get( $this->trellocard->toArray(), 'due'));
    }

    /** @test */
    public function it_can_set_the_top_position()
    {
        $this->trellocard->top();

        $this->assertEquals('top', Arr::get( $this->trellocard->toArray(), 'pos'));
    }

    /** @test */
    public function it_can_set_the_bottom_position()
    {
        $this->trellocard->bottom();

        $this->assertEquals('bottom', Arr::get( $this->trellocard->toArray(), 'pos'));
    }

    /** @test */
    public function it_can_set_a_numeric_position()
    {
        $this->trellocard->position(5);

        $this->assertEquals(5, Arr::get( $this->trellocard->toArray(), 'pos'));
    }

    /** @test */
    public function it_can_be_sent_to_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token', '');

        $this->trellocard
            ->name('CardName')
            ->description('MyDescription')
            ->idlist('')
            ->create();

        $this->assertNotNull(Arr::get( $this->trellocard->toArray(), 'idBoard'));
        $this->assertNotNull(Arr::get( $this->trellocard->toArray(), 'idList'));
    }

    /** @test */
    public function it_can_be_updated_on_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token', '');

        $this->trellocard
            ->name('CardName')
            ->description('MyDescription')
            ->idlist('')
            ->update();

        $this->assertNotNull(Arr::get( $this->trellocard->toArray(), 'idBoard'));
        $this->assertNotNull(Arr::get( $this->trellocard->toArray(), 'idList'));
    }

    /** @test */
    public function it_can_be_deleted_on_trello()
    {
        Config::set('laravel-trello.auth.key',   '');
        Config::set('laravel-trello.auth.token', '');

        $card = new TrelloCard;

        $card->name('CardName')
            ->description('MyDescription')
            ->idlist('')
            ->create();

        $deleted = $card->delete();

        $this->assertEquals('deleted',$deleted);
    }
}
