- To insert data into a 'footer' table in Laravel, you need to create a seeder file,
register it in DatabaseSeeder.php, and execute the seed command.Here is how you can set it up:1. Create the SeederRun this command in your terminal to create a new seeder class:bash

-php artisan make:seeder FooterTableSeeder

2. Define the Seed DataOpen database/seeders/FooterTableSeeder.php and add your insertion logic in the run method:php<?php

- namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('footer')->insert([
            'section_title' => 'Quick Links',
            'content'       => 'About Us, Contact, Privacy Policy',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
3. Register the Seeder in DatabaseSeeder.phpOpen database/seeders/DatabaseSeeder.php and call the newly created seeder inside the run method using the $this->call() method:php<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            FooterTableSeeder::class,
        ]);
    }
}
4. Run the SeederTo run the seeder, execute the following command in your terminal:bash
- php artisan db:seed
Optional: Seed Only the Footer SeederIf you only want to run this specific seeder without executing all seeders in DatabaseSeeder.php, use the --class option:bash
- php artisan db:seed --class=FooterTableSeeder