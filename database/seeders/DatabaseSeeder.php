<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Job;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]
        );

        // ── Categories ────────────────────────────────────
        $categoryData = [
            ['name' => 'Engineering', 'icon' => '⚙️',  'color' => 'bg-blue-50 text-blue-700',    'order' => 1],
            ['name' => 'Design',      'icon' => '🎨',  'color' => 'bg-pink-50 text-pink-700',    'order' => 2],
            ['name' => 'Marketing',   'icon' => '📣',  'color' => 'bg-orange-50 text-orange-700','order' => 3],
            ['name' => 'Finance',     'icon' => '💰',  'color' => 'bg-green-50 text-green-700',  'order' => 4],
            ['name' => 'Healthcare',  'icon' => '🏥',  'color' => 'bg-red-50 text-red-700',      'order' => 5],
            ['name' => 'Education',   'icon' => '📚',  'color' => 'bg-yellow-50 text-yellow-700','order' => 6],
            ['name' => 'Data',        'icon' => '📊',  'color' => 'bg-violet-50 text-violet-700','order' => 7],
            ['name' => 'Sales',       'icon' => '🤝',  'color' => 'bg-purple-50 text-purple-700','order' => 8],
        ];

        foreach ($categoryData as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                $cat
            );
        }

        // ── Jobs ──────────────────────────────────────────
        $jobs = [
            [
                'title'        => 'Senior Frontend Developer',
                'company'      => 'TechNova Inc.',
                'location'     => 'San Francisco, CA',
                'category'     => 'Engineering',
                'type'         => 'Full-time',
                'salary_range' => '$120k - $160k',
                'description'  => 'We are looking for a Senior Frontend Developer to join our growing team.',
                'requirements' => '5+ years of React experience\nStrong TypeScript skills\nExperience with Next.js',
                'is_featured'  => true,
            ],
            [
                'title'        => 'Product Designer',
                'company'      => 'DesignCraft Studio',
                'location'     => 'Remote',
                'category'     => 'Design',
                'type'         => 'Remote',
                'salary_range' => '$90k - $120k',
                'description'  => 'Join our creative team as a Product Designer.',
                'requirements' => '3+ years product design experience\nFigma proficiency\nUser research skills',
                'is_featured'  => true,
            ],
            [
                'title'        => 'Backend Engineer',
                'company'      => 'CloudStack',
                'location'     => 'Austin, TX',
                'category'     => 'Engineering',
                'type'         => 'Full-time',
                'salary_range' => '$110k - $150k',
                'description'  => 'We need a skilled Backend Engineer to help scale our infrastructure.',
                'requirements' => 'Strong PHP/Laravel or Node.js skills\nMySQL and Redis experience',
                'is_featured'  => false,
            ],
            [
                'title'        => 'Marketing Manager',
                'company'      => 'GrowthLab',
                'location'     => 'New York, NY',
                'category'     => 'Marketing',
                'type'         => 'Full-time',
                'salary_range' => '$80k - $110k',
                'description'  => 'GrowthLab is seeking a data-driven Marketing Manager.',
                'requirements' => '4+ years marketing experience\nSEO/SEM expertise',
                'is_featured'  => false,
            ],
            [
                'title'        => 'DevOps Engineer',
                'company'      => 'InfraCore',
                'location'     => 'Seattle, WA',
                'category'     => 'Engineering',
                'type'         => 'Full-time',
                'salary_range' => '$130k - $170k',
                'description'  => 'InfraCore is hiring a DevOps Engineer to manage our cloud infrastructure.',
                'requirements' => 'AWS/GCP expertise\nTerraform/Ansible skills\nKubernetes administration',
                'is_featured'  => true,
            ],
            [
                'title'        => 'Data Analyst',
                'company'      => 'DataWave',
                'location'     => 'Chicago, IL',
                'category'     => 'Data',
                'type'         => 'Full-time',
                'salary_range' => '$75k - $100k',
                'description'  => 'DataWave is looking for a Data Analyst to turn raw data into actionable insights.',
                'requirements' => 'SQL proficiency\nPython/R skills\nTableau or Power BI',
                'is_featured'  => false,
            ],
        ];

        foreach ($jobs as $jobData) {
            // category name দিয়ে category_id বের করা
            $category = Category::where('name', $jobData['category'])->first();

            Job::firstOrCreate(
                ['title' => $jobData['title'], 'company' => $jobData['company']],
                array_merge($jobData, [
                    'category_id' => $category?->id,
                ])
            );
        }
    }
}