<?php

namespace App\Http\Controllers;

use App\Models\ExamMark;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function student_login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'roll_no' => 'required',
                'dob' => 'required',
            ]
        );

        if (!$validator->fails()) {
            $roll_no = $request->roll_no;
            $dob = $request->dob;

            $student = Student::where('roll_no', $roll_no)->where('dob', $dob)->first();

            if ($student) {
                $token = sha1(time());
                $student->token = $token;
                $student->save();

                return array("token" => $token);
            } else {
                return response()->json(['message' => "invalid data"], 201);
            }
        } else {
            return response()->json(['message' => $validator->errors()->first()], 201);
        }
    }

    public function student_info(Request $request)
    {
        $token = $request->token;
        $student = Student::with(['batch', 'standard'])->where('token', $token)->first();
        $subjects = [];

        $student_subject = StudentSubject::with('subject')->where('student_id', $student->id)->get();
        foreach($student_subject as $ss)
        {
            $subjects[] = $ss->subject->name;
        }
        if ($student) {
            return array(
                "name" => $student->name,
                "roll_no" => $student->roll_no,
                "dob" => $student->dob,
                "father_name" => $student->father_name,
                "class" => $student->standard->name,
                "batch" => $student->batch->name,
                "subjects" => implode(", ", $subjects),
            );

            $student->toArray();
        } else {
            return response()->json(['message' => "invalid data"], 502);
        }
    }

    public function classes_info(Request $request)
    {
        $token = $request->token;
        $student = Student::where('token', $token)->first();

        /* Class Schedules */
        $student_subject = StudentSubject::with('subject')->where('student_id', $student->id)->get();

        $subject_id = [];
        $subject_name = [];

        foreach ($student_subject as $da) {
            $subject_id[] = $da->subject_id;
            $subject_name[] = $da->subject->name;
        }

        $today_class = Schedule::with('subject')->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id)->whereIn('subject_id', $subject_id)->whereDate('class_at', date("Y-m-d"))->orderBy('class_at', "ASC")->limit(5)->get();
        $upcomming_class = Schedule::with('subject')->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id)->whereIn('subject_id', $subject_id)->whereDate('class_at', ">", date("Y-m-d"))->orderBy('class_at', "ASC")->limit(5)->get();
        $today_class_array = [];
        $upcomming_class_array = [];

        foreach ($today_class as $index => $tc) {
            $today_class_array[] = array("s_no" => $index + 1, "subject" => $tc->subject->name, "time" => date('h:i A', strtotime($tc->class_at)));
        }

        foreach ($upcomming_class as $index => $tc) {
            $upcomming_class_array[] = array("s_no" => $index + 1, "subject" => $tc->subject->name, "date" => date('d-M-Y', strtotime($tc->class_at)), "time" => date('h:i A', strtotime($tc->class_at)));
        }

        return array("today_class" => $upcomming_class_array, "upcomming_class" => $upcomming_class_array);
    }

    public function marks_info(Request $request)
    {
        $token = $request->token;
        $student = Student::where('token', $token)->first();

        $exam_marks = ExamMark::whereHas('exam', function ($q) use ($student) {
            $q->where('batch_id', $student->batch_id)->where('standard_id', $student->standard_id);
        })->where('student_id', $student->id)->orderBy('id', 'DESC')->get();

        $exam_marks_array = [];
        foreach ($exam_marks as $index => $em) {
            $exam_marks_array[] = array("s_no" => $index + 1, "subject" => $em->exam->subject->name, "exam_name" => $em->exam->name, "mark" => $em->mark);
        }

        return $exam_marks_array;
    }
}
