<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // প্রোডাকশনে ফেকার এরর এড়াতে সরাসরি DB কুয়েরি দিয়ে অ্যাডমিন ইউজার তৈরি
        DB::table('users')->insert([
            'name' => 'Mahbub WebDev',
            'username' => 'mahbub',
            'email' => 'mahbub.webdev@gmail.com',
            'password' => Hash::make('12321232'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert default data row for home slides
        DB::table('home_slides')->insert([
            'home_slide' => 'upload/home_slide/default.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert default data row for the about page
        DB::table('abouts')->insert([
            'title' => 'I am a Professional Web Developer',
            'short_title' => 'About Me',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->call([
            FooterTableSeeder::class,
        ]);
    }
}
