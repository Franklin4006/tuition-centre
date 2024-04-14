<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{

    public function dashboard()
    {
        $students = Student::where('status', 1)->get()->count();
        $teachers =User::where('role_id', 2)->where('status', 1)->get()->count();
        $subjects = Subject::where('status', 1)->get()->count();
        $today_class = Schedule::whereDate('class_at', date("Y-m-d"))->get()->count();
        return view('admin.dashboard', compact('students', 'teachers', 'subjects', 'today_class'));
    }

    public function change_password()
    {
        return view('admin.change_password');
    }

    public function update_password(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:8|string'
        ]);

        $auth = Auth::user();

        // The passwords matches
        if (!Hash::check($request->get('current_password'), $auth->password)) {
            return back()->with('error', "Current Password is Invalid");
        }

        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return back()->with('success', "Password Changed Successfully");
    }
}
