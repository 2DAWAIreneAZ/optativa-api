<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Style;

class StyleSeeder extends Seeder
{
    public function run(): void
    {
        $styles = [
            // CATEGORÍAS DE PLATZI API (deben coincidir con el mapeo en ProductController)
            ['name' => 'Clothes', 'difficulty' => 'easy'],
            ['name' => 'Electronics', 'difficulty' => 'medium'],
            ['name' => 'Furniture', 'difficulty' => 'medium'],
            ['name' => 'Shoes', 'difficulty' => 'easy'],
            ['name' => 'Miscellaneous', 'difficulty' => 'medium'],
            
            // CATEGORÍAS ADICIONALES SIN API
            ['name' => 'Clásico', 'difficulty' => 'easy'],
            ['name' => 'Moderno', 'difficulty' => 'medium'],
            ['name' => 'Fade', 'difficulty' => 'hard'],
            ['name' => 'Undercut', 'difficulty' => 'medium'],
            ['name' => 'Pompadour', 'difficulty' => 'hard'],
        ];

        foreach ($styles as $style) {
            Style::firstOrCreate(
                ['name' => $style['name']], 
                $style
            );
        }

        $this->command->info('✅ Styles created successfully!');
        $this->command->info('📦 API-enabled categories: Clothes, Electronics, Furniture, Shoes, Miscellaneous');
        $this->command->info('🌐 Using Platzi Fake Store API: https://api.escuelajs.co/api/v1/products');
    }
}
