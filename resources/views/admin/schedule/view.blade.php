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
                            <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#add-modal">
                                <i class="bi-plus-circle"></i> Create New
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pd-20 bg-white border-radius-4 box-shadow">

                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Subject</th>
                                <th>Class Time</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Schedule
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form id="add-form">
                    @csrf

                    <input type="hidden" name="edit_id" id="edit_id">
                    <input type="hidden" name="batch_id" value="{{ $batch_id }}">
                    <input type="hidden" name="standard_id" value="{{ $standard_id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Subject</label>
                            <select class="form-control" name="subject_id">
                                <option value="">Select Subject</option>
                                @foreach ($subject as $sub)
                                    <option value="{{ $sub->subject->id }}">{{ $sub->subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label>Classes</label>
                        <div id="class_div"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="store-btn">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('addscript')
    <script type="text/javascript">
        var table;
        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                order: [[2, 'desc']],
                ajax: {
                    url: "{{ route('schedule.fatch') }}",
                    data: {
                        standard: {{ $standard_id }},
                        batch: {{ $batch_id }}
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'subject.name',
                        name: 'subject'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        }

        $("#add-form").validate({
            submitHandler: function(form) {

                $("#store-btn").prop("disabled", true);

                var data = new FormData(form);
                var url = "{{ route('schedule.store') }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.warning(response.message);
                        }
                        $("#store-btn").prop("disabled", false);
                        $("#add-modal").modal("hide");
                        table.clear().draw();
                    },
                    error: function(code) {
                        toastr.warning(code.statusText);
                    },
                });
                return false;
            }
        });

        $(document).on("click", ".add-btn", function() {

            var html_content = `<div class="row">
                        <div class="col-sm-5">
                            <input type="date" name="date[]" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="col-sm-5">
                            <input type="time" name="time[]" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" id="add_file_btn" class="btn btn-primary btn-sm"> + </button>
                            <button type="button" id="sub_file_btn" class="btn btn-primary btn-sm"> - </button>
                        </div>
                    </div>`;

            $("#class_div").html(html_content);

            $("#edit_id").val("");
            $("#teachers-form")[0].reset();
            $('#permission').trigger('change');
        });

        $(document).on("click", "#add_file_btn", function() {

            var html_content = `<div class="row mt-2">
                        <div class="col-sm-5">
                            <input type="date" name="date[]" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="col-sm-5">
                            <input type="time" name="time[]" class="form-control">
                        </div>
                    </div>`;

            $("#class_div").append(html_content);
        });

        $(document).on("click", "#sub_file_btn", function() {
            $('#class_div').children().last().remove();
        });

        $(document).on("click", ".delete-btn", function() {
            var edit_id = $(this).data('id');
            $("#edit_id").val(edit_id);
            $("#delete-confirm-text").text("Are you confirm to Delete this Schedule");
            $("#delete-confirm-modal").modal("show");
        });

        $(document).on("click", "#confirm-yes-btn", function() {
            var edit_id = $("#edit_id").val();

            $.ajax({
                url: "{{ route('schedule.delete') }}",
                method: "POST",
                data: {
                    'id': edit_id,
                    '_token': '{{ csrf_token() }}',
                },
                dataType: "json",
                success: function(response) {
                    table.clear().draw();
                },
                error: function(code) {
                    toastr.warning(code.statusText);
                },
            });
        });
    </script>
@endsection
