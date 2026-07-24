<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('updated_at', 'desc')->get();

        return response()->view('sitemap', compact('blogs'))
                         ->header('Content-Type', 'text/xml'); // [১.৩.৩, ১.৩.৬]
    }
}
