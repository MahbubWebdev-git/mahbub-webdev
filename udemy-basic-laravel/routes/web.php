<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Home\HomeSliderController;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\PortfolioController;
use App\Http\Controllers\Home\MultiImageController;
use App\Http\Controllers\Home\BlogCategoryController;
use App\Http\Controllers\Home\BlogController;
use App\Http\Controllers\Home\FooterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\UserController; 


/*
|--------------------------------------------------------------------------
| Public Routes (সাধারণ ভিজিটরদের জন্য সম্পূর্ণ উন্মুক্ত)
|--------------------------------------------------------------------------
*/
// Dynamic XML Sitemap Route
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Frontend Public Controller Routes
Route::controller(DemoController::class)->group(function () {
    Route::get('/', 'HomeMain')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'ContactMethode')->name('contact.page');
});

// Extra Frontend Inner Detailed Pages
Route::get('/portfolio/details/{id}', [PortfolioController::class, 'PortfolioDetails'])->name('portfolio.details'); 
Route::get('/portfolio', [PortfolioController::class, 'HomePortfolio'])->name('home.portfolio');
Route::get('/blog/details/{id}', [BlogController::class, 'BlogDetails'])->name('blog.details');
Route::get('/category/blog/{id}', [BlogController::class, 'CategoryBlog'])->name('category.blog');
Route::get('/blog', [BlogController::class, 'HomeBlog'])->name('home.blog');

// Contact Message Store Route
Route::post('/store/message', [ContactController::class, 'StoreMessage'])->name('store.message');


/*
|--------------------------------------------------------------------------
| Protected Admin Routes (শুধু ভেরিফাইড এবং অ্যাডমিন অনুমোদিত ইউজারদের জন্য)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin.approved'])->group(function () {

    // ১. মেইন ড্যাশবোর্ড স্ক্রিন
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('dashboard');

    // ইউজার রোল ও পারমিশন ম্যানেজমেন্ট রাউটস (অনুমোদিত অ্যাডমিনদের জন্য)
    Route::get('/admin/all-users', [UserController::class, 'allUsers'])->name('admin.all.users');
    Route::post('/admin/user/toggle-approval/{id}', [UserController::class, 'toggleApproval'])->name('admin.user.toggle');
    Route::post('/admin/user/update-role/{id}', [UserController::class, 'updateRolePermissions'])->name('admin.user.update.role');


    // ২. নতুন ইউজার পেন্ডিং ও অ্যাপ্রুভাল গেটওয়ে
    Route::get('/admin/pending-users', [UserController::class, 'pendingUsers'])->name('admin.pending.users');
    Route::post('/admin/user/approve/{id}', [UserController::class, 'approveUser'])->name('admin.user.approve');

    // ৩. প্রোফাইল এবং পাসওয়ার্ড ম্যানেজমেন্ট রাউটস
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/logout', 'destroy')->name('admin.logout');
        Route::get('/admin/profile', 'Profile')->name('admin.profile');
        Route::get('/edit/profile/edit/{id}', 'EditProfile')->name('edit.profile');
        Route::match(['get', 'post'], '/store/profile', 'StoreProfile')->name('store.profile');
        Route::get('/change/password', 'ChangePassword')->name('change.password');
        Route::post('/update/password', 'UpdatePassword')->name('update.password');
    });

    // ৪. হোম স্লাইডার কন্ট্রোল রাউটস
    Route::controller(HomeSliderController::class)->group(function () {
        Route::get('/home/slide', 'HomeSlider')->name('home.slide');
        Route::post('/update/slide', 'UpdateSlider')->name('update.slider');
    });

    // ৫. অ্যাবাউট পেজ এবং মাল্টি-ইমেজ কন্ট্রোল রাউটস
    Route::controller(AboutController::class)->group(function () {
        Route::get('/about/page', 'AboutPage')->name('about.page');
        Route::post('/update/about', 'UpdateAbout')->name('update.about');
        Route::get('/about/multi/image', 'AboutMultiImage')->name('about.multi.image');
        Route::post('/store/multi/image', 'StoreMultiImage')->name('store.multi.image');
        Route::get('/all/multi/image', 'AllMultiImage')->name('all.multi.image');
        Route::get('/edit/multi/image/{id}', 'EditMultiImage')->name('edit.multi.image');
        Route::post('/update/multi/image', 'UpdateMultiImage')->name('update.multi.image');
        Route::get('/delete/multi/image/{id}', 'DeleteMultiImage')->name('delete.multi.image');
    });

    // ৬. পোর্টফোলিও কন্ট্রোল রাউটস
    Route::controller(PortfolioController::class)->group(function () {
        Route::get('/all/portfolio', 'AllPortfolio')->name('all.portfolio');
        Route::get('/add/portfolio', 'AddPortfolio')->name('add.portfolio');
        Route::post('/store/portfolio', 'StorePortfolio')->name('store.portfolio');
        Route::get('/edit/portfolio/{id}', 'EditPortfolio')->name('edit.portfolio');
        Route::post('/update/portfolio', 'UpdatePortfolio')->name('update.portfolio');
        Route::get('/delete/portfolio/{id}', 'DeletePortfolio')->name('delete.portfolio');
    });

    // ৭. ব্লগ ক্যাটাগরি কন্ট্রোল রাউটস
    Route::controller(BlogCategoryController::class)->group(function () {
        Route::get('/all/blog/category', 'AllBlogCategory')->name('all.blog.category');  
        Route::get('/add/blog/category', 'AddBlogCategory')->name('add.blog.category');
        Route::post('/store/blog/category', 'StoreBlogCategory')->name('store.blog.category');
        Route::get('/edit/blog/category/{id}', 'EditBlogCategory')->name('edit.blog.category');
        Route::post('/update/blog/category/{id}', 'UpdateBlogCategory')->name('update.blog.category');
        Route::get('/delete/blog/category/{id}', 'DeleteBlogCategory')->name('delete.blog.category');
    });

    // ৮. ব্লগ পোস্ট কন্ট্রোল রাউটস
    Route::controller(BlogController::class)->group(function () {
        Route::get('/all/blog', 'AllBlog')->name('all.blog');
        Route::get('/add/blog', 'AddBlog')->name('add.blog');
        Route::post('/store/blog', 'StoreBlog')->name('store.blog');
        Route::get('/edit/blog/{id}', 'EditBlog')->name('edit.blog');
        Route::post('/update/blog', 'UpdateBlog')->name('update.blog');
        Route::get('/delete/blog/{id}', 'DeleteBlog')->name('delete.blog');
    });

    // ৯. ফুটার সেটআপ রাউটস
    Route::controller(FooterController::class)->group(function () {
        Route::get('/footer/setup', 'FooterSetup')->name('footer.setup');
        Route::post('/update/footer', 'UpdateFooter')->name('update.footer');
    });

    // ১০. কন্টাক্ট মেসেজ ভিউ রাউটস
    Route::controller(ContactController::class)->group(function () {
        Route::get('/contact/messages', 'ContactMessages')->name('contact.messages');
        Route::get('/delete/message/{id}', 'DeleteMessage')->name('delete.message');
    });
});

/*
|--------------------------------------------------------------------------
| System Utilities (ডেভলপমেন্ট ক্লিনআপ টুলস)
|--------------------------------------------------------------------------
*/
Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return "Database completely cleaned and seeded successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

require __DIR__ . '/auth.php';
