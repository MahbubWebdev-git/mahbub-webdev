<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ১. পেন্ডিং এবং অ্যাপ্রুভড সব ইউজারের লিস্ট একসাথে দেখার জন্য
    public function allUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.all_users', compact('users'));
    }

    // ২. স্লাইডার/টগল বাটনে ক্লিক করলে বাটন দিয়ে ইনস্ট্যান্ট অ্যাপ্রুভ/ডিসেবল করার জন্য (Ajax বা নরমাল পোস্ট)
    public function toggleApproval($id)
    {
        $user = User::findOrFail($id);
        // ০ থাকলে ১ হবে, ১ থাকলে ০ হবে (টগল)
        $user->is_approved = $user->is_approved == 1 ? 0 : 1; 
        $user->save();

        return redirect()->back()->with([
            'message' => 'User status updated successfully!',
            'alert-type' => 'success'
        ]);
    }

    // ৩. ইউজারের রোল এবং কোন কোন পেজ এডিট করতে পারবে তা আপডেট করার জন্য
    public function updateRolePermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        
        // যদি কোনো পেজ সিলেক্ট না করা হয় তবে খালি অ্যারে সেট হবে
        $user->allowed_pages = $request->allowed_pages ?? [];
        $user->save();

        return redirect()->back()->with([
            'message' => 'Role and page permissions updated successfully!',
            'alert-type' => 'success'
        ]);
    }
}
