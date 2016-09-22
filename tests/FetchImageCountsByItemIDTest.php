<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FetchImageCountsByItemIDTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testDontSeeLaravelOnHomePage()
    {
        $this->visit('/')
            ->see('State Library DAM Extraction Tool')
            ->dontSee('Laravel 5');
    }
}
