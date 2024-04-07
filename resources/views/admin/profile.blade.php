@extends('admin.layouts.master')
@section('title', 'Profile')

@section('content')
    <style>
        .form-group .col-form-label {
            font-weight: 600;
        }

        .my-pull-right {
            float: right;
        }

        .avatar-photo {
            height: 100%;
            object-fit: cover;
        }

        @media only screen and (max-width: 600px) {
            .my-pull-right {
                float: left;
            }
        }
    </style>
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <h4>Profile</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Profile
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 mb-30">
                        <div class="pd-20 card-box height-100-p">
                            <form id="profile-form">
                                @csrf
                                <div class="profile-photo">
                                    <label class="edit-avatar"><i class="fa fa-pencil"></i>
                                        <input type="file" name="profile_photo" id="profile-photo-input"
                                            style="width: 0px;height: 0px;" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp">
                                    </label>
                                    @if (auth()->user()->profile)
                                        <img src="{{ url('uploads/profile_photo') }}/{{ auth()->user()->profile }}"
                                            alt="" id="profile-photo-img" class="avatar-photo" />
                                    @else
                                        <img src="{{ url('images/user-icon.jpeg') }}" alt="" id="profile-photo-img"
                                            class="avatar-photo" />
                                    @endif
                                </div>
                                <h5 class="text-center h5 mb-0">{{ auth()->user()->name }}</h5>
                                <p class="text-center text-muted font-14">
                                    Admin
                                </p>
                                <div class="profile-info">
                                    <h5 class="mb-20 h5 text-primary">Contact Information</h5>

                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label my-pull-right">Name : </label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ auth()->user()->name }}" required pattern="[a-zA-Z0-9\s]{3,}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label my-pull-right">Mobile : </label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" name="mobile_no" minlength="10"
                                                maxlength="10" value="{{ auth()->user()->mobile_no }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label my-pull-right">Email ID : </label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" name="email"
                                                value="{{ auth()->user()->email }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-7">
                                            <button class="btn btn-primary">
                                                <i class="icon-copy bi bi-check2-circle"></i> Submit
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('addscript')
    <script>
        $(document).ready(function() {
            $('#profile-photo-input').change(function check(input) {
                var selectedValue = $(this).val();
                var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
                if ($.inArray(selectedValue.split('.').pop().toLowerCase(), fileExtension) == -1) {
                    $('#profile-photo-input').val("");
                    $('#profile-photo-img').attr('src', "");
                } else {
                    var selected_file = $('#profile-photo-input').get(0).files[0];
                    selected_file = window.URL.createObjectURL(selected_file);
                    $('#profile-photo-img').attr('src', selected_file);
                }
            });
        });

        $("#profile-form").validate({
            submitHandler: function(form) {
                var data = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.profile.update') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // alert("ok");
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.warning(response.message);
                        }
                    }
                });
                return false;
            }
        });
    </script>
@endsection
