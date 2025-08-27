@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <p>Selamat datang, <span class="font-semibold">{{ $user->name }}</span>!</p>
@endsection
