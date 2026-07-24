# Code
- php artisan make:controller AdminController
- php artisan route:list
- php artisan make:model HomeSlide -m
- php artisan migrate
- php artisan make:controller Home/HomeSliderController
- composer require intervention/image
- php artisan make:model About -m
- php artisan migrate
- php artisan make:controller Home/AboutController
- {!! strip_tags($aboutpage->long_description) !!}
- php artisan make:model MultiImage -m

- If you are using "imageIntervention" V3 and face this error then you have to update some function because fome functions are replaced.
- Image::make() relpaced by Image::read().
- Image::canvas() replaced by Image::create() and so on. Just replace function. Worked for me, hope work for you.
- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
- <script src="{{ asset('backend/assets/js/code.js') }}"></script>

- php artisan make:model Portfolio -m
- php artisan migrate
- php artisan make:controller Home/PortfollioController

# Category
- php artisan make:model BlogCategory -m
- php artisan migrate
- php artisan make:controller Home/BlogCategoryController
- php artisan make:model Blog -m
- php artisan migrate
- php artisan make:controller Home/BlogController

# Show DB info details from .env
- grep -E "DB_HOST|DB_DATABASE|DB_USERNAME|DB_PASSWORD" .env

# DB backup
- mysqldump -u root -p'Dream@db25' udemy_basic_project > udemy_basic_project.sql && zip udemy_basic_project.zip udemy_basic_project.sql && rm udemy_basic_project.sql

# DB restore
- unzip -p udemy_basic_project.zip udemy_basic_project.sql | mysql -u root -p'Dream@db25' udemy_basic_project

# Alternative: Step-by-Step Backup Method 
- If you prefer to extract the file first to inspect it, run these two commands:
- unzip udemy_basic_project.zip
- mysql -u root -p'Dream@db25' udemy_basic_project < udemy_basic_project.sql

# HTML Descrition
- <p>{{ strip_tags(Str::limit($item->blog_description, 200)) }}</p>
- <p>{!! Str::limit($item->blog_description, 200) !!}</p>
- Cat Descriptin = <p>{!! Str::words($item->blog_description, 30, '...') !!}</p>
- All Blog = <p>{{ Str::limit(strip_tags($item->blog_description), 200) }}</p>

# Footer
- php artisan make:model Footer -m
- $table->id();
            $table->string('number')->nullable();
            $table->text('short_description')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('behance')->nullable();
            $table->string('instagram')->nullable();
            $table->string('copyright')->nullable();
            $table->timestamps();
- php artisan migrate
- php artisan make:controller Home/FooterController

# Contact
- php artisan make:model Contact -m
- php artisan migrate
- php artisan make:controller ContactController

# Footer Seed
- php artisan make:seeder FooterTableSeeder
- Into FooterTableSeeder.php
- public function run(): void
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

    - Into DatabaseSeeder.php
    - $this->call([
            FooterTableSeeder::class,
        ]);
    - php artisan db:seed --class=FooterTableSeeder

# for migration table
- php artisan make:migration add_is_approved_to_users_table --table=users
- php artisan migrate
# ১. ইউজারের কাছে থ্যাঙ্ক ইউ মেইল পাঠানোর ক্লাস
- php artisan make:notification UserRegistrationPendingNotification

# ২. অ্যাডমিনকে অ্যালার্ট মেইল পাঠানোর ক্লাস
- php artisan make:notification AdminNewUserAlertNotification
- php artisan tinker

# user permissin table
- php artisan make:migration add_role_and_permissions_to_users_table --table=users










