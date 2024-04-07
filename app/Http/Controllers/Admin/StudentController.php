<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Standard;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\SubjectStandard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function index()
    {
        $standard = Standard::get();
        $batch = Batch::get();
        return view('admin.students.index', compact('standard', 'batch'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($request->edit_id) {
            $students = Student::find($request->edit_id);
            StudentSubject::where('student_id', $request->edit_id)->delete();
            $message = "Student Updated Successfully";
        } else {
            $students = new Student();
            $students->status = 1;
            $message = "Student Created Successfully";
        }

        if (!$validator->fails()) {

            $students->name = $request->name;
            $students->roll_no = $request->roll_no;
            $students->dob = $request->dob;
            $students->father_name = $request->father_name;
            $students->standard_id = $request->standard_id;
            $students->batch_id = $request->batch_id;
            $students->contact_no = $request->contact_no;
            $students->address = $request->address;

            $students->save();

            $subject_list = $request->subjects;

            foreach($subject_list as $sub)
            {
                $stud_sub = new StudentSubject();
                $stud_sub->student_id = $students->id;
                $stud_sub->subject_id = $sub;
                $stud_sub->save();
            }

            return array("success" => 1, "message" => $message);
        } else {
            return array("success" => "0", "message" =>  $validator->errors()->first());
        }
    }
    public function show($id)
    {
        $students = Student::find($id);
        $subject_id = StudentSubject::where('student_id', $id)->pluck('subject_id')->toArray();

        $sub_standard = SubjectStandard::with('subject')->where('standard_id', $students->standard_id)->get();
        $subjects = [];
        foreach($sub_standard as $sub)
        {
            if(in_array($sub->subject_id, $subject_id)){
                $checked = 1;
            }else{
                $checked = 0;
            }
            $subjects[] = array("id" => $sub->subject_id, "name" => $sub->subject->name, "checked" => $checked);
        }
        return array("students" => $students, "subjects" => $subjects);
    }

    public function fetch()
    {
        $data = Student::get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">InActive</span>';
                }
            })->addColumn('dob_text', function ($row) {
                    return date("d-m-Y", strtotime($row->status));
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">';

                $btn .= '<button data-id="' . $row->id . '" class="edit-btn dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</button>';

                if ($row->status == 0) {
                    $btn .= '<button data-id="' . $row->id . '" data-status="Active" class="change-status-btn dropdown-item"><i class="bi bi-arrow-up-right-circle"></i>Active</button>';
                } else {
                    $btn .= '<button data-id="' . $row->id . '" data-status="InActive" class="change-status-btn dropdown-item"><i class="bi bi-arrow-up-right-circle"></i>InActive</button>';
                }

                $btn .= '<button data-id="' . $row->id . '" class="delete-btn dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</button>';

                $btn .= '</div>
                        </div>';

                return $btn;
            })
            ->rawColumns(['action', 'status', 'dob_text'])
            ->make(true);
    }

    public function destroy($id)
    {
        Student::find($id)->delete();
        return array("success" => 1, "message" => "Deleted Successfully");
    }

    public function chage_status(Request $request)
    {
        $id = $request->edit_id;
        $status = $request->status;
        $students = Student::find($id);
        if ($status == "Active") {
            $students->status = 1;
        } else if ($status == "InActive") {
            $students->status = 0;
        }
        $students->save();
        return array("status" => 1, "message" => "Status Updated successfully");
    }

    public function fetch_standard_subject(Request $request)
    {
        $standard_id = $request->standard_id;
        $sub_standard = SubjectStandard::with('subject')->where('standard_id', $standard_id)->get();
        $subjects = [];
        foreach($sub_standard as $sub)
        {
            $subjects[] = array("id" => $sub->subject_id, "name" => $sub->subject->name);
        }
        return $subjects;
    }
}
