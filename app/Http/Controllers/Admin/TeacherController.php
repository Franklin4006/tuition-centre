<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
    public function index()
    {
        $batch  = Batch::orderBy('id', 'DESC')->get();
        $standard  = Standard::get();
        $subject  = Subject::get();
        return view('admin.teachers.index', compact('batch', 'standard', 'subject'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($request->edit_id) {
            $teacher = User::find($request->edit_id);
            $message = "Teacher Updated Successfully";
            TeacherSubject::where('teacher_id', $request->edit_id)->delete();
        } else {
            $teacher = new User();
            $teacher->status = 1;
            $teacher->role_id = 2;
            $message = "Teacher Created Successfully";
        }

        if (!$validator->fails()) {

            $teacher->name = $request->name;
            $teacher->mobile_no = $request->mobile;
            $teacher->email = $request->email;
            if($request->password)
            {
                $teacher->password = Hash::make($request->password);
            }
            $teacher->save();

            $batch = $request->batch;
            $standard = $request->standard;
            $subject = $request->subject;

            for ($i = 0; $i < count($batch); $i++) {
                $teach_sub = new TeacherSubject();
                $teach_sub->teacher_id = $teacher->id;
                $teach_sub->batch_id = $batch[$i];
                $teach_sub->standard_id = $standard[$i];
                $teach_sub->subject_id = $subject[$i];
                $teach_sub->save();
            }

            return array("success" => 1, "message" => $message);
        } else {
            return array("success" => "0", "message" =>  $validator->errors()->first());
        }
    }
    public function show($id)
    {
        $teacher = User::find($id);
        $teach_sub = TeacherSubject::where('teacher_id', $id)->get();
        return array("teacher" => $teacher, "teach_sub" => $teach_sub);
    }

    public function fetch()
    {
        $data = User::where('role_id', 2)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">InActive</span>';
                }
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
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        TeacherSubject::where('teacher_id', $id)->delete();
        return array("success" => 1, "message" => "Deleted Successfully");
    }

    public function chage_status(Request $request)
    {
        $id = $request->edit_id;
        $status = $request->status;
        $tender = User::find($id);
        if ($status == "Active") {
            $tender->status = 1;
        } else if ($status == "InActive") {
            $tender->status = 0;
        }
        $tender->save();
        return array("status" => 1, "message" => "Status Updated successfully");
    }
}
