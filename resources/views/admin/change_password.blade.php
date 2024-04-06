@extends('admin.layouts.master')
@section('title', 'CabConnect | Dashboard')

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
                                <h4>Change Password</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('admin/dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Change Password
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="pd-20 bg-white border-radius-4 box-shadow mb-30" style="min-height: 200px;">
                    <form action="{{ url('admin/update-password') }}" method="POST">
                        @csrf

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class = "alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label class="col-form-label my-pull-right">Current Password : </label>
                            </div>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label class="col-form-label my-pull-right">New Password : </label>
                            </div>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" name="new_password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label class="col-form-label my-pull-right">Confirm Password : </label>
                            </div>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" name="new_password_confirmation" required>
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

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
