<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Style;

class ImportFakeStoreProducts extends Command
{
    protected $signature = 'import:fakestoreproducts';
    protected $description = 'Import products from FakeStore API';

    public function handle()
    {
        $this->info('Fetching products from FakeStore API...');

        try {
            $response = Http::get('https://fakestoreapi.com/products');
            
            if ($response->successful()) {
                $products = $response->json();
                
                $this->info('Found ' . count($products) . ' products. Importing...');
                
                foreach ($products as $productData) {
                    $this->importProduct($productData);
                }
                
                $this->info('Import completed successfully!');
            } else {
                $this->error('Failed to fetch products from API');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    private function importProduct($data)
    {
        // Mapear categorías de FakeStore a estilos
        $categoryMap = [
            'electronics' => 'Electronics',
            'jewelery' => 'Jewelry',
            "men's clothing" => "Men's Clothing",
            "women's clothing" => "Women's Clothing"
        ];

        $styleName = $categoryMap[$data['category']] ?? 'General';
        $style = Style::where('name', $styleName)->first();

        if (!$style) {
            $style = Style::where('name', 'General')->first();
        }

        // Crear o actualizar producto
        Product::updateOrCreate(
            ['name' => $data['title']],
            [
                'id_style' => $style->id,
                'price' => $data['price'],
                'description' => $data['description'],
                'image' => $data['image'],
                'stock' => rand(10, 100)
            ]
        );

        $this->line('Imported: ' . $data['title']);
    }
}