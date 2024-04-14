<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student | Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container-fluid p-3 bg-light text-white text-center">
        <h2 class="text-danger">{{ env('APP_NAME') }}</h2>
    </div>

    <div class="container mt-1">
        <div class="row">
            @if (count($today_class) > 0)
                <div class="col-sm-6 mt-2">
                    <h4>Today's Classes</h4>
                    <table class="table table-bordered table-hover">
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
            @endif

            <div class="col-sm-6 mt-2">
                <h4>Upcomming Classes</h4>
                <table class="table table-bordered table-hover">
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

</body>

</html>
