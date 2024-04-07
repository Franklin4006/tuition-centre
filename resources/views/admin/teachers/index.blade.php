@extends('admin.layouts.master')
@section('title', 'Teachers')

@section('content')

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">
                                <h4>List of Teachers</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Teachers
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#teachers-modal">
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
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
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

    <div class="modal fade" id="teachers-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Teachers
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form id="teachers-form">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <input type="hidden" name="edit_status" id="edit_status">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="mobile" id="mobile">
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Password</label>
                                <input type="text" class="form-control" name="password" id="password">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label>Classes</label>
                        </div>
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
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teachers.fetch') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'mobile_no',
                    name: 'mobile_no'
                },
                {
                    data: 'email',
                    name: 'email'
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

        $("#teachers-form").validate({
            rules: {
                name: {
                    required: true,
                },
            },
            submitHandler: function(form) {

                $("#store-btn").prop("disabled", true);

                var data = new FormData(form);
                var url = "{{ route('teachers.store') }}";
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
                        $("#teachers-modal").modal("hide");
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
                url: "{{ route('teachers.index') }}/" + edit_id,
                dataType: "json",
                success: function(response) {
                    var teacher = response.teacher;
                    var teach_sub = response.teach_sub;
                    $("#name").val(teacher.name);
                    $("#mobile").val(teacher.mobile_no);
                    $("#email").val(teacher.email);

                    var html_content = '';
                    for (var i = 0; i < teach_sub.length; i++) {
                        html_content += `<div class="row form-group">
                                    <div class="col-sm-3">
                                        <select class="form-control" name="batch[]">
                                            <option value="">Select Bacth</option>`;
                                            @foreach ($batch as $ba)
                                            if(teach_sub[i].batch_id == {{ $ba->id }})
                                                {
                                                    html_content += `<option selected value="{{ $ba->id }}">{{ $ba->name }}</option>`;
                                                }
                                                else
                                                {
                                                    html_content += `<option value="{{ $ba->id }}">{{ $ba->name }}</option>`;
                                                }
                                            @endforeach
                                        html_content += `</select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="standard[]">
                                            <option value="">Select Standard</option>`;
                                            @foreach ($standard as $sta)
                                                if(teach_sub[i].standard_id == {{ $sta->id }})
                                                {
                                                    html_content += `<option selected value="{{ $sta->id }}">{{ $sta->name }}</option>`;
                                                }
                                                else
                                                {
                                                    html_content += `<option value="{{ $sta->id }}">{{ $sta->name }}</option>`;
                                                }
                                            @endforeach
                                        html_content += `</select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="subject[]">
                                            <option value="">Select Subject</option>`;
                                            @foreach ($subject as $sub)
                                            if(teach_sub[i].standard_id == {{ $sub->id }})
                                                {
                                                    html_content += `<option selected value="{{ $sub->id }}">{{ $sub->name }}</option>`;
                                                }
                                                else
                                                {
                                                    html_content += `<option value="{{ $sub->id }}">{{ $sub->name }}</option>`;
                                                }
                                            @endforeach
                                        html_content += `</select>
                                    </div>`;
                        if (i == 0) {
                            html_content += `<div class="col-sm-2">
                                            <button type="button" id="add_file_btn" class="btn btn-primary btn-sm"> + </button>
                                            <button type="button" id="sub_file_btn" class="btn btn-primary btn-sm"> - </button>
                                        </div>`;
                        }
                        html_content += `</div>`;
                    }

                    $("#class_div").html(html_content);
                    $("#teachers-modal").modal("show");
                },
                error: function(code) {
                    toastr.warning(code.statusText);
                },
            });
        });

        $(document).on("click", ".add-btn", function() {

            var html_content = `<div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" name="batch[]">
                                            <option value="">Select Bacth</option>
                                            @foreach ($batch as $ba)
                                                <option value="{{ $ba->id }}">{{ $ba->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="standard[]">
                                            <option value="">Select Standard</option>
                                            @foreach ($standard as $sta)
                                                <option value="{{ $sta->id }}">{{ $sta->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="subject[]">
                                            <option value="">Select Subject</option>
                                            @foreach ($subject as $sub)
                                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                            @endforeach
                                        </select>
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
        $(document).on("click", ".delete-btn", function() {
            var edit_id = $(this).data('id');
            $("#edit_id").val(edit_id);
            $("#delete-confirm-text").text("Are you confirm to Delete this Standard");
            $("#delete-confirm-modal").modal("show");
        });

        $(document).on("click", "#confirm-yes-btn", function() {
            var edit_id = $("#edit_id").val();

            $.ajax({
                url: "{{ route('teachers.index') }}/" + edit_id,
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
                url: "{{ route('teachers.chage_status') }}",
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

        $(document).on("click", "#add_file_btn", function() {

            var html_content = `<div class="row mt-2">
                                    <div class="col-sm-3">
                                        <select class="form-control" name="batch[]">
                                            <option value="">Select Bacth</option>
                                            @foreach ($batch as $ba)
                                                <option value="{{ $ba->id }}">{{ $ba->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="standard[]">
                                            <option value="">Select Standard</option>
                                            @foreach ($standard as $sta)
                                                <option value="{{ $sta->id }}">{{ $sta->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="subject[]">
                                            <option value="">Select Subject</option>
                                            @foreach ($subject as $sub)
                                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>`;

            $("#class_div").append(html_content);
        });

        $(document).on("click", "#sub_file_btn", function() {
            $('#class_div').children().last().remove();
        });
    </script>
@endsection
