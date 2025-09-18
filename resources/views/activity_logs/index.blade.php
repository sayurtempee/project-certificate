@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>

        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">User</th>
                    <th class="border p-2">Action</th>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="border p-2">{{ $log->user->name ?? 'System' }}</td>
                        <td class="border p-2">{{ $log->action }}</td>
                        <td class="border p-2">{{ $log->description }}</td>
                        <td class="border p-2">{{ $log->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-2">Belum ada log.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
