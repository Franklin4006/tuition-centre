<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Schedule;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\SubjectStandard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    public function index()
    {
        $standard = Standard::where('status', 1)->get();
        $batch = Batch::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('admin.schedule.index', compact('standard', 'batch'));
    }
    public function view($batch, $standard)
    {
        $subject_list = SubjectStandard::with('subject')->where('standard_id', $standard)->get();
        return view('admin.schedule.view', ['subject' => $subject_list, 'batch_id' => $batch, 'standard_id' => $standard]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required',
        ]);

        if (!$validator->fails()) {
            $date = $request->date;
            $time = $request->time;

            for ($i = 0; $i < count($date); $i++) {
                $schedule = new Schedule();
                $schedule->batch_id = $request->batch_id;
                $schedule->standard_id = $request->standard_id;
                $schedule->subject_id = $request->subject_id;
                $schedule->class_at = date("Y-m-d H:i:s", strtotime($date[$i] . " " . $time[$i]));
                $schedule->save();
            }

            return array("success" => 1, "message" => "Schedule added Successfully");
        } else {
            return array("success" => "0", "message" =>  $validator->errors()->first());
        }
    }

    public function fatch(Request $request)
    {
        $batch = $request->batch;
        $standard = $request->standard;
        $data = Schedule::with('subject')->where('batch_id', $batch)->where('standard_id', $standard)->get();
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

                // $btn .= '<button data-id="' . $row->id . '" class="edit-btn dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</button>';

                // if ($row->status == 0) {
                //     $btn .= '<button data-id="' . $row->id . '" data-status="Active" class="change-status-btn dropdown-item"><i class="bi bi-arrow-up-right-circle"></i>Active</button>';
                // } else {
                //     $btn .= '<button data-id="' . $row->id . '" data-status="InActive" class="change-status-btn dropdown-item"><i class="bi bi-arrow-up-right-circle"></i>InActive</button>';
                // }

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
        Schedule::find($id)->delete();
        return array("success" => 1, "message" => "Deleted Successfully");
    }
}
