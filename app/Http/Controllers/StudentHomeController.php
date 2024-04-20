<?php

namespace App\Http\Controllers;

use App\Models\ExamMark;
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
        $student = Student::with('standard')->with('batch')->find($student_id);

        /* Class Schedules */
        $student_subject = StudentSubject::with('subject')->where('student_id', $student_id)->get();

        $subject_id = [];
        $subject_name = [];

        foreach ($student_subject as $da) {
            $subject_id[] = $da->subject_id;
            $subject_name[] = $da->subject->name;
        }

        $today_class = Schedule::with('subject')->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id)->whereIn('subject_id', $subject_id)->whereDate('class_at', date("Y-m-d"))->orderBy('class_at', "ASC")->get();
        $upcomming_class = Schedule::with('subject')->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id)->whereIn('subject_id', $subject_id)->whereDate('class_at', ">",date("Y-m-d"))->orderBy('class_at', "ASC")->limit(10)->get();

        /* Marks */

        $exam_marks = ExamMark::whereHas('exam', function($q) use ($student) {
            $q->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id);
        })->where('student_id', $student_id)->orderBy('id', 'DESC')->get();

        return view('student.dashboard', compact('exam_marks', 'student', 'subject_name', 'today_class', 'upcomming_class'));
    }
}
