<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function HomeMain()
    {
        if (view()->exists('frontend.index')) {
            return view('frontend.index');
        }

        return response('Home page placeholder', 200);
    }

    public function about()
    {
        $aboutpage = About::find(1) ?? new About();

        if (view()->exists('frontend.about_page')) {
            return view('frontend.about_page', compact('aboutpage'));
        }

        return response('About page placeholder', 200);
    }

    public function ContactMethode()
    {
        if (view()->exists('frontend.contact')) {
            return view('frontend.contact');
        }

        return response('Contact page placeholder', 200);
    }
}

