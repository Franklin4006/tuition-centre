<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student | Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container-fluid p-3 bg-light text-white text-center">
        <h2 class="text-danger">{{ env('APP_NAME') }}</h2>
    </div>

    <div class="container mt-3">

        <!-- Nav pills -->
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#schedules">Class Schedules</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#exam">Exam Result</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane active">
                <div class="card mt-3">
                    <div class="card-header">My Profile</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th style="width: 30%;">Name</th>
                                <th>:</th>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <th>Roll No</th>
                                <th>:</th>
                                <td>{{ $student->roll_no }}</td>
                            </tr>
                            <tr>
                                <th>DOB</th>
                                <th>:</th>
                                <td>{{ date('d-m-Y', strtotime($student->dob)) }}</td>
                            </tr>
                            <tr>
                                <th>Father Name</th>
                                <th>:</th>
                                <td>{{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <th>Standard & Batch</th>
                                <th>:</th>
                                <td>{{ $student->standard->name }} - {{ $student->batch->name }}</td>
                            </tr>
                            <tr>
                                <th>Subject</th>
                                <th>:</th>
                                <td>{{ implode(', ', $subject_name) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div id="schedules" class="tab-pane">

                <div class="row">
                    @if (count($today_class) > 0)
                        <div class="col-sm-12">
                            <div class="card mt-3">
                                <div class="card-header">Today's Classes</div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Subject</th>
                                            <th>Time</th>
                                        </tr>
                                        @foreach ($today_class as $index => $tc)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tc->subject->name }}</td>
                                                <td>{{ date('h:i A', strtotime($tc->class_at)) }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        <div class="card mt-3">
                            <div class="card-header">Upcomming Classes</div>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                    @if (count($upcomming_class) > 0)
                                        @foreach ($upcomming_class as $index => $tc)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tc->subject->name }}</td>
                                                <td>{{ date('d-M-Y', strtotime($tc->class_at)) }}</td>
                                                <td>{{ date('h:i A', strtotime($tc->class_at)) }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No Class Found</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="exam" class="tab-pane">

                <div class="card mt-3">
                    <div class="card-header">Upcomming Classes</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th>S.No</th>
                                <th>Exam Date</th>
                                <th>Subject</th>
                                <th>Exam Name</th>
                                <th>Mark</th>
                            </tr>
                            @foreach ($exam_marks as $index => $em)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $em->exam->date }}</td>
                                    <td>{{ $em->exam->subject->name }}</td>
                                    <td>{{ $em->exam->name }}</td>
                                    <td>{{ $em->mark }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
