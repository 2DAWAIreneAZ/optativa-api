<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Style;

class StyleSeeder extends Seeder
{
    public function run(): void
    {
        $styles = [
            ['name' => 'Electronics', 'difficulty' => 'medium'],
            ['name' => 'Jewelery', 'difficulty' => 'hard'],
            ['name' => 'Men\'s Clothing', 'difficulty' => 'easy'],
            ['name' => 'Women\'s Clothing', 'difficulty' => 'easy'],
            ['name' => 'General', 'difficulty' => 'easy']
        ];

        foreach ($styles as $style) {
            Style::create($style);
        }
    }
}
