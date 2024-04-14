<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherHomeController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user();

        $teacher_subject = TeacherSubject::where('teacher_id', $teacher->id)->get();

        $today_class = [];
        $upcomming_class = [];
        $today_class_array = [];
        $upcomming_class_array = [];
        foreach ($teacher_subject as $ts) {
            $today_class_array[] = Schedule::with('subject')->with('standard')->where('batch_id', $ts->batch_id)->where('standard_id', $ts->standard_id)->where('subject_id', $ts->subject_id)->whereDate('class_at', date("Y-m-d"))->get()->toArray();
            $upcomming_class_array[] = Schedule::with('subject')->with('standard')->where('batch_id', $ts->batch_id)->where('standard_id', $ts->standard_id)->where('subject_id', $ts->subject_id)->whereDate('class_at', ">", date("Y-m-d"))->orderBy('class_at', "ASC")->limit(5)->get()->toArray();
        }

        foreach ($today_class_array as $tc) {
            foreach ($tc as $row) {
                $today_class[] = $row;
            }
        }

        foreach ($upcomming_class_array as $uc) {
            foreach ($uc as $row) {
                $upcomming_class[] = $row;
            }
        }

        array_multisort(array_column($today_class, 'class_at'), SORT_ASC, $today_class);
        array_multisort(array_column($upcomming_class, 'class_at'), SORT_ASC, $upcomming_class);

        return view('teacher.dashboard', compact('today_class', 'upcomming_class'));
    }
}
