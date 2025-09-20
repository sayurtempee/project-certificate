<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Activity Logs PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Activity Logs (Teacher)</h2>
    <p>Generated at: {{ now()->format('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada log.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
