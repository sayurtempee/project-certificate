@extends('layouts.dashboard')

@section('title', 'Sertifikat Download Image')

@section('dashboard-content')
    @include('layouts.partials.swal')
    <div class="container mx-auto px-5 py-8">
        <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-1">
            <i class="bi bi-cloud-arrow-down"></i> Download Sertifikat Image (LANDSCAPE)
        </h3>
    </div>
@endsection
