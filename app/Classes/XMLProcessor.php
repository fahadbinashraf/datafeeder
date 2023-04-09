<?php
namespace App\Classes;

use Exception;
use SimpleXMLElement;
use XMLReader;

class XMLProcessor
{
    public function process($file)
    {
        $reader = new XMLReader();

        if (!$reader->open($file)) {
            throw new Exception("Error: Unable to open XML file: $file");
        }

        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'item') {
                $itemNode = new SimpleXMLElement($reader->readOuterXml());
                yield [
                    'entity_id' => (integer) $itemNode->entity_id,
                    'category_name' => (string) $itemNode->CategoryName,
                    'sku' => (string) $itemNode->sku,
                    'name' => (string) $itemNode->name,
                    'description' => (string) $itemNode->description,
                    'short_desc' => (string) $itemNode->shortdesc,
                    'price' => (string) $itemNode->price || 0,
                    'link' => (string) $itemNode->link,
                    'image' => (string) $itemNode->image,
                    'brand' => (string) $itemNode->Brand,
                    'rating' => (integer) $itemNode->Rating || 0,
                    'caffeine_type' => (string) $itemNode->CaffeineType,
                    'count' => (integer) $itemNode->Count || 0,
                    'flavored' => (string) $itemNode->Flavored,
                    'seasonal' => (string) $itemNode->Seasonal,
                    'in_stock' => (string) $itemNode->InStock,
                    'facebook' => (boolean) $itemNode->Facebook,
                    'is_k_cup' => (boolean) $itemNode->IsKCup,
                ];
            }
        }

        $reader->close();
    }
}