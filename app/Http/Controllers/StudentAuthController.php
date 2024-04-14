<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    public function login()
    {
        $student_login = session('student_login');
        if ($student_login) {
            return redirect(url('student'));
        }
        return view('student.login');
    }
    public function login_submit(Request $request)
    {
        $credentials = $request->validate([
            'roll_no' => 'required',
            'dob' => 'required'
        ]);

        $student = Student::where('roll_no', $request->roll_no)->where("dob", $request->dob)->first();
        if ($student) {
            session(['student_login' => true]);
            session(['student_id' => $student->id]);
            session(['student_name' => $student->name]);
            return redirect(url('student/dashboard'));
        }else
        {
            return back()->withErrors([
                'email' => 'Your provided credentials do not match in our records.',
            ]);
        }

    }
}
