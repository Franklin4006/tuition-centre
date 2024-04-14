<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentHomeController extends Controller
{
    public function dashboard()
    {
        $student_id = session('student_id');
        $student = Student::find($student_id);

        $student_subject = StudentSubject::with('subject')->where('student_id', $student_id)->get();

        $subject_id = [];

        foreach ($student_subject as $da) {
            $subject_id[] = $da->subject_id;
        }

        $today_class = Schedule::with('subject')->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id)->whereIn('subject_id', $subject_id)->whereDate('class_at', date("Y-m-d"))->orderBy('class_at', "ASC")->get();
        $upcomming_class = Schedule::with('subject')->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id)->whereIn('subject_id', $subject_id)->whereDate('class_at', ">",date("Y-m-d"))->orderBy('class_at', "ASC")->limit(10)->get();

        return view('student.dashboard', compact('student_subject', 'today_class', 'upcomming_class'));
    }
}
