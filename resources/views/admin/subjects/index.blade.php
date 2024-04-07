@extends('admin.layouts.master')
@section('title', 'Subjects')

@section('content')

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">
                                <h4>List of Subjects</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Subjects
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#subjects-modal">
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

    <div class="modal fade" id="subjects-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Subjects
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form id="subjects-form">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <input type="hidden" name="edit_status" id="edit_status">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label>Standards</label>
                            <select name="standards[]" id="standards" class="form-control select2" multiple
                                style="width: 100%;">
                                @foreach ($standards as $st)
                                    <option value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
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
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('subjects.fetch') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
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

        $("#subjects-form").validate({
            rules: {
                name: {
                    required: true,
                },
                standards: {
                    required: true,
                },
            },
            submitHandler: function(form) {

                $("#store-btn").prop("disabled", true);

                var data = new FormData(form);
                var url = "{{ route('subjects.store') }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success == 1) {
                            toastr.success(response.message);
                        } else {
                            toastr.warning(response.message);
                        }
                        $("#store-btn").prop("disabled", false);
                        $("#subjects-modal").modal("hide");
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
                url: "{{ route('subjects.index') }}/" + edit_id,
                dataType: "json",
                success: function(response) {
                    $("#name").val(response.name);
                    var sub_standard = response.sub_standard;
                    for (var i = 0; i < sub_standard.length; i++) {
                        $("#standards").find("option[value='" + sub_standard[i] + "']").prop(
                            "selected", "selected");
                    }
                    $("#standards").trigger('change');
                    $("#subjects-modal").modal("show");
                },
                error: function(code) {
                    toastr.warning(code.statusText);
                },
            });
        });

        $(document).on("click", ".add-btn", function() {
            $("#edit_id").val("");
            $("#subjects-form")[0].reset();
            $("#standards").trigger('change');
            $('#permission').trigger('change');
        });
        $(document).on("click", ".delete-btn", function() {
            var edit_id = $(this).data('id');
            $("#edit_id").val(edit_id);
            $("#delete-confirm-text").text("Are you confirm to Delete this  Subject");
            $("#delete-confirm-modal").modal("show");
        });

        $(document).on("click", "#confirm-yes-btn", function() {
            var edit_id = $("#edit_id").val();

            $.ajax({
                url: "{{ route('subjects.index') }}/" + edit_id,
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
            $("#status-confirm-text").text("Are you confirm to " + status + " this Subject");
            $("#status-confirm-modal").modal("show");
        });

        $(document).on("click", "#status-confirm-btn", function() {
            var edit_id = $("#edit_id").val();
            var status = $("#edit_status").val();
            $("#status-confirm-btn").prop("disabled", true);

            $.ajax({
                url: "{{ route('subjects.chage_status') }}",
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
    </script>
@endsection
