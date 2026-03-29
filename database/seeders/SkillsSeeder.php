<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Skill;

class SkillsSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Backend
            ['name' => 'PHP',         'category' => 'Backend'],
            ['name' => 'Laravel',     'category' => 'Backend'],
            ['name' => 'Node.js',     'category' => 'Backend'],
            ['name' => 'Python',      'category' => 'Backend'],
            ['name' => 'Django',      'category' => 'Backend'],
            ['name' => 'Go',          'category' => 'Backend'],
            ['name' => 'Java',        'category' => 'Backend'],
            ['name' => 'Spring Boot', 'category' => 'Backend'],
            ['name' => '.NET',        'category' => 'Backend'],

            // Frontend
            ['name' => 'React',       'category' => 'Frontend'],
            ['name' => 'Vue.js',      'category' => 'Frontend'],
            ['name' => 'Angular',     'category' => 'Frontend'],
            ['name' => 'TypeScript',  'category' => 'Frontend'],
            ['name' => 'JavaScript',  'category' => 'Frontend'],
            ['name' => 'Tailwind CSS','category' => 'Frontend'],
            ['name' => 'Next.js',     'category' => 'Frontend'],
            ['name' => 'Livewire',    'category' => 'Frontend'],

            // Mobile
            ['name' => 'Flutter',     'category' => 'Mobile'],
            ['name' => 'React Native','category' => 'Mobile'],
            ['name' => 'Kotlin',      'category' => 'Mobile'],
            ['name' => 'Swift',       'category' => 'Mobile'],

            // Database
            ['name' => 'MySQL',       'category' => 'Database'],
            ['name' => 'PostgreSQL',  'category' => 'Database'],
            ['name' => 'MongoDB',     'category' => 'Database'],
            ['name' => 'Redis',       'category' => 'Database'],

            // DevOps
            ['name' => 'Docker',      'category' => 'DevOps'],
            ['name' => 'Kubernetes',  'category' => 'DevOps'],
            ['name' => 'AWS',         'category' => 'DevOps'],
            ['name' => 'Linux',       'category' => 'DevOps'],
            ['name' => 'CI/CD',       'category' => 'DevOps'],

            // Tools
            ['name' => 'Git',         'category' => 'Tools'],
            ['name' => 'REST APIs',   'category' => 'Tools'],
            ['name' => 'GraphQL',     'category' => 'Tools'],
            ['name' => 'Figma',       'category' => 'Design'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(
                ['name' => $skill['name']],
                [
                    'slug'     => Str::slug($skill['name']),
                    'category' => $skill['category'],
                ]
            );
        }

        $this->command->info('Skills seeded: ' . count($skills) . ' skills');
    }
}