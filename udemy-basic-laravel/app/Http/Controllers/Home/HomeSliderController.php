<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    public function HomeSlider()
    {
        $homeSlides = HomeSlide::find(1);
        if (!$homeSlides) {
            $homeSlides = new HomeSlide();
            $homeSlides->id = 1;
            $homeSlides->title = '';
            $homeSlides->short_title = '';
            $homeSlides->video_url = '';
            $homeSlides->home_slide = '';
            $homeSlides->save();
        }
        return view('admin.home_slide.home_slide_all', compact('homeSlides'));
    }

    public function UpdateSlider(Request $request)
    { 
        $slide_id = $request->id;

        if ($request->file('home_slide')) {
            $image = $request->file('home_slide');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('upload/home_slide');

            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            Image::read($image)->resize(600, 600)->save($destinationPath . '/' . $name_gen);
            $save_url = 'upload/home_slide/' . $name_gen;

            HomeSlide::findOrFail($slide_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'video_url' => $request->video_url,
                'home_slide' => $save_url,
            ]);

            $notificatio = array(
                'message' => 'Home Slide Updated with image Successfully',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notificatio);
        } else {
            HomeSlide::findOrFail($slide_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'video_url' => $request->video_url,
            ]);

            $notificatio = array(
                'message' => 'Home Slide Updated without image Successfully',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notificatio);
        }
    }
}
