<?php

namespace App\Commands;

use App\Classes\XMLProcessor;
use App\Models\Product;
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
            // Create a new XML processor instance
            $processor = new XMLProcessor($filename);

            $this->info("Truncating the products table...");
            // Truncate the products table before importing
            Product::truncate();

            $this->info("Starting to import products...");
            // Initialize the batch array, batch size and the count
            $batch = [];
            $batchSize = 500;
            $count = 0;
            foreach ($processor->process() as $item) {
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
            // Log the exception message
            Log::error('Import error: ' . $e->getMessage());
        }
    }
}