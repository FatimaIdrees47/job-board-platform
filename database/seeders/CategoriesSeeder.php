<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Backend Development',   'icon' => '⚙️',  'color' => '#7B5EA7'],
            ['name' => 'Frontend Development',  'icon' => '🎨',  'color' => '#22D3EE'],
            ['name' => 'Mobile Development',    'icon' => '📱',  'color' => '#10B981'],
            ['name' => 'DevOps & Cloud',        'icon' => '☁️',  'color' => '#F59E0B'],
            ['name' => 'Data Science & AI',     'icon' => '🤖',  'color' => '#F43F5E'],
            ['name' => 'UI/UX Design',          'icon' => '✏️',  'color' => '#A78BFA'],
            ['name' => 'Cybersecurity',         'icon' => '🔒',  'color' => '#EF4444'],
            ['name' => 'QA & Testing',          'icon' => '🧪',  'color' => '#14B8A6'],
            ['name' => 'Project Management',    'icon' => '📋',  'color' => '#8B5CF6'],
            ['name' => 'Blockchain & Web3',     'icon' => '🔗',  'color' => '#F97316'],
            ['name' => 'Game Development',      'icon' => '🎮',  'color' => '#EC4899'],
            ['name' => 'Technical Writing',     'icon' => '📝',  'color' => '#6366F1'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'slug'  => Str::slug($category['name']),
                    'icon'  => $category['icon'],
                    'color' => $category['color'],
                ]
            );
        }

        $this->command->info('Categories seeded: '.count($categories).' categories');
    }
}