<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
    public function index()
    {
        $standard = Standard::get();
        return view('admin.batchs.index', compact('standard'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($request->edit_id) {
            $standard = Batch::find($request->edit_id);
            $message = "Batch Updated Successfully";
        } else {
            $standard = new Batch();
            $standard->status = 1;
            $message = "Batch Created Successfully";
        }

        if (!$validator->fails()) {

            $standard->name = $request->name;
            // $standard->standard_id = $request->standard;
            $standard->save();

            return array("success" => 1, "message" => $message);
        } else {
            return array("success" => "0", "message" =>  $validator->errors()->first());
        }
    }
    public function show($id)
    {
        $standard = Batch::find($id);
        return $standard;
    }

    public function fetch()
    {
        $data = Batch::orderBy('id', 'DESC')->get();
        // dd($data);
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
        Batch::find($id)->delete();
        return array("success" => 1, "message" => "Deleted Successfully");
    }

    public function chage_status(Request $request)
    {
        $id = $request->edit_id;
        $status = $request->status;
        $tender = Batch::find($id);
        if ($status == "Active") {
            $tender->status = 1;
        } else if ($status == "InActive") {
            $tender->status = 0;
        }
        $tender->save();
        return array("status" => 1, "message" => "Status Updated successfully");
    }
}
