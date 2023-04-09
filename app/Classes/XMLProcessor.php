<?php
namespace App\Classes;

use Exception;
use Illuminate\Support\Carbon;
use SimpleXMLElement;
use XMLReader;

class XMLProcessor
{

    function __construct(private $file)
    {
    }
    public function process()
    {
        $reader = new XMLReader();

        $reader->open(base_path($this->file));
        $now = Carbon::now()->toDateTimeString();

        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'item') {
                $itemNode = new SimpleXMLElement($reader->readOuterXml());
                yield [
                    'entity_id' => $itemNode->entity_id,
                    'category_name' => $itemNode->CategoryName,
                    'sku' => $itemNode->sku,
                    'name' => $itemNode->name,
                    'description' => $itemNode->description,
                    'short_desc' => $itemNode->shortdesc,
                    'price' => !empty($itemNode->price) ? $itemNode->price : 0,
                    'link' => $itemNode->link,
                    'image' => $itemNode->image,
                    'brand' => $itemNode->Brand,
                    'rating' => !empty($itemNode->Rating) ? $itemNode->Rating : 0,
                    'caffeine_type' => $itemNode->CaffeineType,
                    'count' => !empty($itemNode->Count) ? $itemNode->Count : 0,
                    'flavored' => $itemNode->Flavored,
                    'seasonal' => $itemNode->Seasonal,
                    'in_stock' => $itemNode->Instock,
                    'facebook' => $itemNode->Facebook,
                    'is_k_cup' => $itemNode->IsKCup,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        $reader->close();
    }
}