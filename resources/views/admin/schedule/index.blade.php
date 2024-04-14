@extends('admin.layouts.master')
@section('title', 'Schedule')

@section('content')

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">
                                <h4>Scheduling Classes</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Schedule
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-6 text-right">
                            {{-- <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#students-modal">
                                <i class="bi-plus-circle"></i> Create New
                            </button> --}}
                        </div>
                    </div>
                </div>

                <div class="pd-20 bg-white border-radius-4 box-shadow">

                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Batch</th>
                                <th>Standard</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $a =0;
                            foreach ($batch as $row) {
                                foreach ($standard as $row2) {
                                    $a++;
                                ?>

                            <tr>
                                <td>{{ $a }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row2->name }}</td>
                                <td>
                                    <a href="{{ route('schedule.view', ['batch' => $row->id, 'standard' => $row2->id]) }}"
                                        class="btn btn-outline-primary btn-sm"><i class="bi bi-wrench-adjustable"></i>
                                        Schedule</a>
                                </td>
                            </tr>

                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('addscript')
    <script type="text/javascript">
        $('.data-table').DataTable();
    </script>
@endsection
