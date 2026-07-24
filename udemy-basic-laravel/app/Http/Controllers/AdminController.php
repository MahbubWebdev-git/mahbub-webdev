<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
     public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
                        'message' => 'User Logged Out Successfully',
                        'alert-type' => 'success'
                    );

        return redirect('/login')->with($notification);
    } // End Method

        public function Profile(){
            $id = Auth::user()->id;
            $adminData = User::find($id);
            return view('admin.admin_profile_view', compact('adminData'));
        } // End Method

            public function EditProfile(){
                $id = Auth::user()->id;
                $editData = User::find($id);
                return view('admin.admin_profile_edit', compact('editData'));
            } // End Method

                public function StoreProfile(Request $request){
                    if ($request->isMethod('get')) {
                        return redirect()->route('edit.profile', Auth::user()->id);
                    }

                    $validated = $request->validate([
                        'name' => 'required|string|max:255',
                        'email' => 'required|email|max:255',
                        'username' => 'required|string|max:255',
                        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                    ]);

                    $id = Auth::user()->id;
                    $data = User::find($id);
                    $data->name = $validated['name'];
                    $data->email = $validated['email'];
                    $data->username = $validated['username'];

                    if ($request->hasFile('profile_image')) {
                        $file = $request->file('profile_image');
                        $destinationPath = public_path('upload/admin_images');

                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }

                        if (!empty($data->profile_image) && file_exists($destinationPath.'/'.$data->profile_image)) {
                            @unlink($destinationPath.'/'.$data->profile_image);
                        }

                        $filename = date('YmdHi').$file->getClientOriginalName();
                        $file->move($destinationPath, $filename);

                        $data->profile_image = $filename;
                    }

                    $data->save();

                    $notification = array(
                        'message' => 'Admin Profile Updated Successfully',
                        'alert-type' => 'info'
                    );
    
                    return redirect()->route('admin.profile')->with($notification);
                } // End Method

                    public function ChangePassword(){
                        return view('admin.admin_change_password');
                    } // End Method

                        public function UpdatePassword(Request $request){
                            $validateData = $request->validate([
                                'oldpassword' => 'required',
                                'newpassword' => 'required',
                                'confirm_password' => 'required|same:newpassword',
                            ]);

                            $hashedPassword = Auth::user()->password;
                            if (Hash::check($request->oldpassword, $hashedPassword)) {
                                $user = User::find(Auth::id());
                                $user->password = Hash::make($request->newpassword);
                                $user->save();
                                session()->flash('message', 'Password Updated Successfully');
                                session()->flash('alert-type', 'success');
                                Auth::logout();
                                return redirect()->route('login');
                            } else {
                                session()->flash('message', 'Old Password is Incorrect');
                                session()->flash('alert-type', 'error');

                                return redirect()->back();
                            }
                        } // End Method

             
}
