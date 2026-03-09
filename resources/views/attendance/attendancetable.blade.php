<!DOCTYPE html>
<html>
<head>
    <title>Firebase Attendance Table</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Firebase Attendance</h1>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Date/Time In</th>
                <th>Day</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Hours Worked</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($firebaseAttendance as $log)
                <tr>
                    <td>{{ $log->employeeID ?? '-' }}</td>
                    <td>{{ $log->employeeName ?? '-' }}</td>
                    <td>{{ $log->department ?? '-' }}</td>
                    <td>{{ $log->dateTimeIn ?? '-' }}</td>
                    <td>{{ $log->day ?? '-' }}</td>
                    <td>{{ $log->timeIn ?? '-' }}</td>
                    <td>{{ $log->timeOut ?? '-' }}</td>
                    <td>{{ $log->hoursWorked ?? '-' }}</td>
                    <td>{{ $log->remarks ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>