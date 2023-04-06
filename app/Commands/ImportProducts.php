<?php

namespace App\Commands;

use App\Models\Product;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ImportProducts extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import-products';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reads data from an xml file and feeds it to the db';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try {
            $xml = simplexml_load_file(base_path('data/feed.xml'));
            foreach ($xml->item as $item) {
                $product = new Product;
                $product->entity_id = $item->entity_id;
                $product->category_name = $item->CategoryName;
                $product->sku = $item->sku;
                $product->name = $item->name;
                $product->short_desc = $item->shortDesc;
                $product->price = $item->price || 0;
                $product->link = $item->link;
                $product->image = $item->image;
                $product->brand = $item->Brand;
                $product->rating = $item->Rating || null;
                $product->caffeine_type = $item->CaffeineType;
                $product->count = $item->Count || null;
                $product->flavored = $item->Flavored;
                $product->seasonal = $item->Seasonal;
                $product->in_stock = $item->InStock;
                $product->facebook = $item->Facebook;
                $product->is_k_cup = $item->IsKCup;
                $product->save();
                $this->info('Added product ' . $product->name . " to the database");
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}