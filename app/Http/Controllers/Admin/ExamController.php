<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ExamImport;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Standard;
use App\Models\Student;
use App\Models\SubjectStandard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ExamController extends Controller
{

    public function index()
    {
        $standard = Standard::where('status', 1)->get();
        $batch = Batch::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('admin.exams.index', compact('standard', 'batch'));
    }
    public function view($batch, $standard)
    {
        $standard_name = Standard::where('id', $standard)->first()->name;
        $batch_name = Batch::where('id', $batch)->first()->name;

        $subject_list = SubjectStandard::with('subject')->where('standard_id', $standard)->get();
        return view('admin.exams.view', ['standard_name' => $standard_name, 'batch_name' => $batch_name, 'subject' => $subject_list, 'batch_id' => $batch, 'standard_id' => $standard]);
    }
    public function pre_upload(Request $request)
    {
        $file = $request->file('file');
        $excel_data = Excel::toCollection(new ExamImport, $file)->toArray();
        $marks_list = $excel_data[0];

        $standard_id = $request->standard_id;
        $batch_id = $request->batch_id;

        $marks_data = [];
        for ($i = 1; $i < count($marks_list); $i++) {

            $roll_no = $marks_list[$i][1];
            $student_name = $marks_list[$i][2];
            $mark = $marks_list[$i][3];

            if ($roll_no && $student_name && $mark) {
                $student_exist = Student::where('roll_no', $roll_no)->where('standard_id', $standard_id)->where('batch_id', $batch_id)->first();
                if ($student_exist) {
                    $marks_data[] = array("roll_no" => $roll_no, "student_name" => $student_name, "mark" => $mark);
                }
            }
        }

        return $marks_data;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required',
        ]);

        $file = $request->file('marks');
        $excel_data = Excel::toCollection(new ExamImport, $file)->toArray();
        $marks_list = $excel_data[0];

        $standard_id = $request->standard_id;
        $batch_id = $request->batch_id;
        $subject_id = $request->subject_id;

        $marks_data = [];
        for ($i = 1; $i < count($marks_list); $i++) {

            $roll_no = $marks_list[$i][1];
            $student_name = $marks_list[$i][2];
            $mark = $marks_list[$i][3];

            if ($roll_no && $student_name && $mark) {
                $student_exist = Student::where('roll_no', $roll_no)->where('standard_id', $standard_id)->where('batch_id', $batch_id)->first();
                if ($student_exist) {
                    $marks_data[] = array("id" => $student_exist->id,  "roll_no" => $roll_no, "student_name" => $student_name, "mark" => $mark);
                }
            }
        }

        $exam = new Exam();
        $exam->batch_id = $batch_id;
        $exam->standard_id = $standard_id;
        $exam->subject_id = $subject_id;
        $exam->name = $request->name;
        $exam->date = $request->date;
        $exam->save();

        foreach ($marks_data as $da) {
            $exam_mark = new ExamMark();
            $exam_mark->exam_id = $exam->id;
            $exam_mark->student_id = $da['id'];
            $exam_mark->mark = $da['mark'];
            $exam_mark->save();
        }

        return array("success" => 1, "message" => "Exam added Successfully");
    }
    public function fatch(Request $request)
    {
        $batch = $request->batch;
        $standard = $request->standard;
        $data = Exam::with('subject')->where('batch_id', $batch)->where('standard_id', $standard)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('time', function ($row) {
                return date("Y-m-d h:i A", strtotime($row->class_at));
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">';

                $btn .= '<button data-id="' . $row->id . '" class="delete-btn dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</button>';

                $btn .= '</div>
                        </div>';

                return $btn;
            })
            ->rawColumns(['action', 'time'])
            ->make(true);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        ExamMark::where('exam_id', $id)->delete();
        Exam::find($id)->delete();
        return array("success" => 1, "message" => "Deleted Successfully");
    }
}
