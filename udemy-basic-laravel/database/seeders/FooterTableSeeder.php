<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert default data row for footers
        DB::table('footers')->insert([
            'number' => '+880 1714 497282',
            'short_description' => 'I am a professional web developer with expertise in creating dynamic and responsive websites. I have a passion for coding and love to bring ideas to life through innovative web solutions.',
            'address' => 'Rajshahi, Bangladesh',
            'email' => 'mahbub.webdev@example.com',
            'facebook' => 'https://www.facebook.com/mahbub.webdev',
            'twitter' => 'https://twitter.com/mahbubwebdev',
            'linkedin' => 'https://www.linkedin.com/in/mahbub-webdev',
            'behance' => 'https://www.behance.net/mahbubwebdev',
            'instagram' => 'https://www.instagram.com/mahbub.webdev',
            'copyright' => '© 2023 Mahbub WebDev. All rights reserved.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
