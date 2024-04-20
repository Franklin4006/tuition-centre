@extends('admin.layouts.master')
@section('title', 'Exam')

@section('content')

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">
                                <h4>{{ $batch_name }} - {{ $standard_name }}</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Exams
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
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Name</th>
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
                        Exam
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
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label>Subject</label>
                                <select class="form-control" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    @foreach ($subject as $sub)
                                        <option value="{{ $sub->subject->id }}">{{ $sub->subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Date</label>
                                <input type="date" class="form-control" name="date" id="date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Marks</label>
                            <input type="file" name="marks" id="marks" class="form-control" required>
                            <a class="text-danger" href="{{ url('marks-sample-excel.xlsx') }}">Sample Excel Download</a>
                        </div>
                        <div id="marks_view_div"></div>
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
                order: [
                    [2, 'desc']
                ],
                ajax: {
                    url: "{{ route('exam.fatch') }}",
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'date',
                        name: 'date'
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

        $(document).on("change", "#marks", function() {
            var marks = $("#marks").val();
            if (marks) {

                var files = $("#marks").prop('files')[0];
                var formdata = new FormData();
                formdata.append('file', files);
                formdata.append('_token', '{{ csrf_token() }}');
                formdata.append('standard_id', {{ $standard_id }});
                formdata.append('batch_id', {{ $batch_id }});

                $.ajax({
                    type: "POST",
                    url: "{{ route('exam.pre_upload') }}",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: 'text',
                    beforeSend: function() {
                        $("#marks").attr("disabled", true);
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        // console.log(data);
                        $("#marks").attr("disabled", false);
                        var html_text = `<div class="table-responsive"><table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Roll No</th>
                                            <th>Student Name</th>
                                            <th>Mark</th>
                                        </tr>
                                    </thead><tbody>`;
                        for (var i = 0; i < data.length; i++) {
                            // console.log(data);
                            html_text += `<tr>
                                    <td>${i+1}</td>
                                    <td>${data[i].roll_no}</td>
                                    <td>${data[i].student_name}</td>
                                    <td>${data[i].mark}</td>
                                </tr>`;
                        }

                        html_text += '</tbody></table></div>';
                        $("#marks_view_div").html(html_text);

                    },
                    error: function(e) {
                        alert(code.statusText);
                    },
                });
            }
        });

        $("#add-form").validate({
            submitHandler: function(form) {

                $("#store-btn").prop("disabled", true);

                var data = new FormData(form);
                var url = "{{ route('exam.store') }}";
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
            $("#edit_id").val("");
            $("#add-form")[0].reset();
        });

        $(document).on("click", ".delete-btn", function() {
            var edit_id = $(this).data('id');
            $("#edit_id").val(edit_id);
            $("#delete-confirm-text").text("Are you confirm to Delete this Exam");
            $("#delete-confirm-modal").modal("show");
        });

        $(document).on("click", "#confirm-yes-btn", function() {
            var edit_id = $("#edit_id").val();

            $.ajax({
                url: "{{ route('exam.delete') }}",
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
