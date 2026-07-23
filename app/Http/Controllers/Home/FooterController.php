<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Footer;

class FooterController extends Controller
{
    public function FooterSetup()
    {
        $allfooter = Footer::find(1);

        if (!$allfooter) {
            $allfooter = new Footer();
            $allfooter->number = '';
            $allfooter->short_description = '';
            $allfooter->address = '';
            $allfooter->email = '';
            $allfooter->facebook = '';
            $allfooter->twitter = '';
            $allfooter->linkedin = '';
            $allfooter->behance = '';
            $allfooter->instagram = '';
            $allfooter->copyright = '';
            $allfooter->save();
        }

        return view('admin.footer.footer_all', compact('allfooter'));
    }

    public function UpdateFooter(Request $request)
    {
        $footer_id = $request->id;

        Footer::findOrFail($footer_id)->update([
            'number' => $request->number,
            'short_description' => $request->short_description,
            'address' => $request->address,
            'email' => $request->email,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'behance' => $request->behance,
            'instagram' => $request->instagram,
            'copyright' => $request->copyright,
        ]);

        $notification = array(
            'message' => 'Footer Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}

