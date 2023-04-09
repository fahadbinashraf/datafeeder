<?php

namespace App\Commands;

use App\Classes\XMLProcessor;
use App\Models\Product;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;

class ImportProducts extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import:products {filename : The name of the XML file to import}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Import products data from an XML file into the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try {
            $filename = $this->argument('filename');
            // create a new XML processor instance
            $processor = new XMLProcessor();

            $this->info("Truncating the products table...");
            // truncate the products table before importing
            Product::truncate();

            $this->info("Starting to import products...");

            // Initialize the batch array, batch size and the count
            $batch = [];
            $batchSize = 1000;
            $count = 0;
            foreach ($processor->process(base_path($filename)) as $item) {
                // Add items to batch array and increase the count
                $batch[] = $item;
                $count++;
                // if we have reached our batch size, insert them into the database
                if ($count % $batchSize == 0) {
                    Product::insert($batch);
                    $batch = [];
                }
            }
            // Insert any remaining items into the database
            if (!empty($batch)) {
                Product::insert($batch);
            }
            // Output a success message with items count
            $this->info("Import complete. $count records inserted.");
        } catch (\Exception $e) {
            // output the error message to console
            $this->error('Import error: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            // Log the exception message
            Log::error('Import error: ' . $e->getMessage() . "\n[stacktrace]\n" . $e->getTraceAsString());
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