<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

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

    

    /**
     * Test to check if the summary of images count by item id works
     * @return void
     */
    public function testFetchSummaryOfImagesCountByItemID()
    {

        $user = User::first();
        $this->actingAs($user);

        $this->visitRoute('details_report')
            ->see('Enter the Item ID for which you want to search')
            ->see('Fetch Report')
            ->type('18778','item_id')
            ->press('Fetch Report')
            ->see('Detail Report for images of Item ID: 18778')
            ->see('Stand Alone')
            ->see('Albums')
            ->dontSee('<h3>Itemized Report</h3>');

    }

    /**
     * Test to check if the itemized view of images count by item id works
     * @return void
     */
    public function testFetchItemizedImagesCountByItemID()
    {

        $user = User::first();
        $this->actingAs($user);

        $this->visitRoute('details_report')
            ->see('Enter the Item ID for which you want to search')
            ->see('Fetch Report')
            ->type('18778','item_id')
            ->check('debug')
            ->press('Fetch Report')
            ->see('Detail Report for images of Item ID: 18778')
            ->see('<h3>Itemized Report</h3>');

    }
}
