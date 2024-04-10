@extends('admin.layouts.master')
@section('title', 'Students')

@section('content')

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">
                                <h4>List of Students</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Students
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#students-modal">
                                <i class="bi-plus-circle"></i> Create New
                            </button>
                        </div>
                    </div>
                </div>
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>Standard</label>
                            <select class="form-control" name="fl_standard" id="fl_standard">
                                <option value="">All</option>
                                @foreach ($standard as $sta)
                                    <option value="{{ $sta->id }}">{{ $sta->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Batch</label>
                            <select class="form-control" name="fl_batch" id="fl_batch">
                                <option value="">All</option>
                                @foreach ($batch as $bat)
                                    <option value="{{ $bat->id }}">{{ $bat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group">
                            <button class="btn btn-primary fl-btn" id="filter_btn"><i class="bi bi-funnel"></i>
                                Filter</button>
                        </div>
                    </div>
                </div>
                <div class="pd-20 bg-white border-radius-4 box-shadow">

                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>DOB</th>
                                <th>Father Name</th>
                                <th>Status</th>
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

    <div class="modal fade" id="students-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Students
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form id="students-form">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <input type="hidden" name="edit_status" id="edit_status">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Roll No</label>
                                <input type="text" class="form-control" name="roll_no" id="roll_no">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>DOB</label>
                                <input type="date" class="form-control" name="dob" id="dob">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Father Name</label>
                                <input type="text" class="form-control" name="father_name" id="father_name">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Standard</label>
                                <select class="form-control" name="standard_id" id="standard_id">
                                    <option value="">Select Standard</option>
                                    @foreach ($standard as $stnd)
                                        <option value="{{ $stnd->id }}">{{ $stnd->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Batch</label>
                                <select class="form-control" name="batch_id" id="batch_id">
                                    <option value="">Select Batch</option>
                                    @foreach ($batch as $bat)
                                        <option value="{{ $bat->id }}">{{ $bat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Subject</label>
                                <div id="subject_div" style="display:flex;"></div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Contact</label>
                                <input type="text" class="form-control" name="contact_no" id="contact_no">
                            </div>
                            <div class="col-sm-12 form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="address" id="address"></textarea>
                            </div>
                        </div>
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

    <div class="modal fade" id="status-confirm-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18">
                    <h4 class="padding-top-30 mb-30 weight-500" id="status-confirm-text">
                        Are you sure you want to continue?
                    </h4>
                    <div class="padding-bottom-30 text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times"></i> NO
                        </button>
                        <button type="button" id="status-confirm-btn" class="btn btn-primary" data-dismiss="modal">
                            <i class="fa fa-check"></i> YES
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('addscript')
    <script type="text/javascript">
        var table;
        $(document).ready(function(){
            initializeDataTable();
        });
        function initializeDataTable() {

            var standard = $("#fl_standard").val();
            var batch = $("#fl_batch").val();

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('students.fetch') }}",
                    data: {
                        standard: standard,
                        batch:batch
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'dob_text',
                        name: 'dob'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        $("#students-form").validate({
            rules: {
                name: {
                    required: true,
                },
            },
            submitHandler: function(form) {

                $("#store-btn").prop("disabled", true);

                var data = new FormData(form);
                var url = "{{ route('students.store') }}";
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
                        $("#students-modal").modal("hide");
                        table.clear().draw();
                    },
                    error: function(code) {
                        toastr.warning(code.statusText);
                    },
                });
                return false;
            }
        });

        $(document).on("click", ".edit-btn", function() {
            var edit_id = $(this).data('id');
            $("#edit_id").val(edit_id);
            $.ajax({
                url: "{{ route('students.index') }}/" + edit_id,
                dataType: "json",
                success: function(response) {
                    var students = response.students;
                    var subjects = response.subjects;
                    $("#name").val(students.name);
                    $("#roll_no").val(students.roll_no);
                    $("#dob").val(students.dob);
                    $("#father_name").val(students.father_name);
                    $("#standard_id").val(students.standard_id);
                    $("#batch_id").val(students.batch_id);
                    $("#contact_no").val(students.contact_no);
                    $("#address").val(students.address);

                    var html_text = '';
                    for (var i = 0; i < subjects.length; i++) {
                        html_text += `<div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="subjects[]" ${(subjects[i].checked == 1)?'checked':''} value="${subjects[i].id}" id="subject_checkbox${subjects[i].id}"/>
                                        <label class="custom-control-label" for="subject_checkbox${subjects[i].id}">${subjects[i].name}&nbsp;&nbsp;&nbsp;</label>
                                    </div>`;
                    }
                    $("#subject_div").html(html_text);

                    $("#students-modal").modal("show");
                },
                error: function(code) {
                    toastr.warning(code.statusText);
                },
            });
        });

        $(document).on("click", ".add-btn", function() {
            $("#edit_id").val("");
            $("#students-form")[0].reset();
            $('#permission').trigger('change');
        });
        $(document).on("click", ".delete-btn", function() {
            var edit_id = $(this).data('id');
            $("#edit_id").val(edit_id);
            $("#delete-confirm-text").text("Are you confirm to Delete this Standard");
            $("#delete-confirm-modal").modal("show");
        });

        $(document).on("click", "#confirm-yes-btn", function() {
            var edit_id = $("#edit_id").val();

            $.ajax({
                url: "{{ route('students.index') }}/" + edit_id,
                method: "DELETE",
                data: {
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


        $(document).on("click", ".change-status-btn", function() {
            var edit_id = $(this).data('id');
            var status = $(this).data('status');
            $("#edit_id").val(edit_id);
            $("#edit_status").val(status);
            $("#status-confirm-text").text("Are you confirm to " + status + " this Standard");
            $("#status-confirm-modal").modal("show");
        });

        $(document).on("click", "#status-confirm-btn", function() {
            var edit_id = $("#edit_id").val();
            var status = $("#edit_status").val();
            $("#status-confirm-btn").prop("disabled", true);

            $.ajax({
                url: "{{ route('students.chage_status') }}",
                data: {
                    edit_id: edit_id,
                    status: status
                },
                method: "GET",
                dataType: "json",
                success: function(response) {
                    table.clear().draw();
                    $("#status-confirm-btn").prop("disabled", false);
                    toastr.success(response.message);
                },
                error: function(code) {
                    toastr.error(code.statusText);
                },
            });
        });

        $(document).on("change", "#standard_id", function() {
            var standard_id = $("#standard_id").val();

            $.ajax({
                url: "{{ route('fetch.standard.subject') }}",
                data: {
                    standard_id: standard_id,
                },
                method: "GET",
                dataType: "json",
                success: function(response) {
                    var html_text = '';
                    for (var i = 0; i < response.length; i++) {
                        html_text += `<div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input" name="subjects[]" value="${response[i].id}" id="subject_checkbox${response[i].id}"/>
                                        <label class="custom-control-label" for="subject_checkbox${response[i].id}">${response[i].name}&nbsp;&nbsp;&nbsp;</label>
                                    </div>`;
                    }
                    $("#subject_div").html(html_text);
                },
                error: function(code) {
                    toastr.error(code.statusText);
                },
            });
        });

        $(document).on("click", "#filter_btn", function() {
            table.destroy();
            initializeDataTable();
        });
    </script>
@endsection
