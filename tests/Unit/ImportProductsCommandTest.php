<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportProductsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up a mock XML file
        $xml = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <catalog>
            <item>
                <entity_id>340</entity_id>
                <CategoryName>Green Mountain Ground Coffee</CategoryName>
                <sku>20</sku>
                <name>Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag</name>
                <description></description>
                <shortdesc>Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, complex dark roast coffee from Green Mountain Ground Coffee.</shortdesc>
                <price>41.6000</price>
                <link>http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html</link>
                <image>http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg</image>
                <Brand>Green Mountain Coffee</Brand>
                <Rating>0</Rating>
                <CaffeineType>Caffeinated</CaffeineType>
                <Count></Count>
                <Flavored>No</Flavored>
                <Seasonal>No</Seasonal>
                <Instock>Yes</Instock>
                <Facebook>1</Facebook>
                <IsKCup>0</IsKCup>
            </item>
            <item>
                <entity_id>342</entity_id>
                <CategoryName>Nestle Hot Chocolate</CategoryName>
                <sku>5000081171</sku>
                <name>Nestle's Rich Hot Chocolate 50 Packets</name>
                <description></description>
                <shortdesc>Nestle's Rich Hot Chocolate 50 Packets bulk quantity prepare 50 individual servings of milk chocolate instant hot cocoa from Nestle Hot Chocolate.</shortdesc>
                <price>11.9900</price>
                <link>http://www.coffeeforless.com/nestles-milk-hot-chocolate-50-packets.html</link>
                <image>http://mcdn.coffeeforless.com/media/catalog/product//n/e/nestle-hot-chocolate-mix-50-packets.png</image>
                <Brand>Nestle</Brand>
                <Rating>5</Rating>
                <CaffeineType></CaffeineType>
                <Count>50</Count>
                <Flavored></Flavored>
                <Seasonal></Seasonal>
                <Instock>Yes</Instock>
                <Facebook>1</Facebook>
                <IsKCup>0</IsKCup>
            </item>
        </catalog>
        XML;

        Storage::put('test.xml', $xml);
    }

    protected function tearDown(): void
    {
        Storage::delete('test.xml');
    }
    /** @test */
    public function it_imports_data_from_an_xml_file()
    {
        // Call the import:products command with the test XML file
        $this->artisan('import:products', ['filename' => 'storage/app/test.xml'])
            ->expectsOutput('Import complete. 2 records inserted.');
        // Verify that the data was imported into the database
        $this->assertDatabaseCount('products', 2);
        $this->assertDatabaseHas('products', [
            'entity_id' => 340,
            'category_name' => "Green Mountain Ground Coffee",
            'sku' => "20",
            'name' => "Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag",
            'description' => "",
            'short_desc' => "Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, complex dark roast coffee from Green Mountain Ground Coffee.",
            'price' => "41.6000",
            'link' => "http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html",
            'image' => "http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg",
            'brand' => "Green Mountain Coffee",
            'rating' => 0,
            'caffeine_type' => "Caffeinated",
            'count' => 0,
            'flavored' => "No",
            'seasonal' => "No",
            'in_stock' => "Yes",
            'facebook' => 1,
            'is_k_cup' => 0,
        ]);
        $this->assertDatabaseHas('products', [
            'entity_id' => 342,
            'category_name' => "Nestle Hot Chocolate",
            'sku' => "5000081171",
            'name' => "Nestle's Rich Hot Chocolate 50 Packets",
            'description' => "",
            'short_desc' => "Nestle's Rich Hot Chocolate 50 Packets bulk quantity prepare 50 individual servings of milk chocolate instant hot cocoa from Nestle Hot Chocolate.",
            'price' => 11.9900,
            'link' => "http://www.coffeeforless.com/nestles-milk-hot-chocolate-50-packets.html",
            'image' => "http://mcdn.coffeeforless.com/media/catalog/product//n/e/nestle-hot-chocolate-mix-50-packets.png",
            'brand' => "Nestle",
            'rating' => 5,
            'caffeine_type' => "",
            'count' => 50,
            'flavored' => "",
            'seasonal' => "",
            'in_stock' => "Yes",
            'facebook' => 1,
            'is_k_cup' => 0,
        ]);
    }

    /** @test */
    public function it_outputs_an_error_message_if_the_file_cannot_be_opened()
    {
        // Call the import:products command with a non-existent file
        $this->artisan('import:products', ['filename' => 'invalid.xml'])
            ->expectsOutput('Import error: XMLReader::open(): Unable to open source data');

        // Verify that no data was imported into the database
        $this->assertDatabaseCount('products', 0);
    }

    /** @test */
    public function it_logs_an_error_if_the_file_cannot_be_opened()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return strpos($message, 'Import error: XMLReader::open(): Unable to open source data') !== false;
            });
        // Call the import:products command with a non-existent file
        $this->artisan('import:products', ['filename' => 'invalid.xml'])
            ->expectsOutput('Import error: XMLReader::open(): Unable to open source data');
        // Verify that no data was imported into the database
        $this->assertDatabaseCount('products', 0);
    }
}