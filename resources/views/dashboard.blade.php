<!-- In dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="h3 text-primary fw-bold">Dashboard</h1>

    <div class="row">
        <!-- Total Requests -->
        <div class="col-md-3">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white">
                    งานแจ้งซ่อมทั้งหมด
                </div>
                <div class="card-body text-center">
                    <h3>{{ $totalRequests }}</h3>
                    <a href="{{ route('repair_requests.index') }}" class="btn btn-primary">View More</a>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-white">
                    รอดำเนินการ (Pending)
                </div>
                <div class="card-body text-center">
                    <h3>{{ $pendingRequests }}</h3>
                    <a href="{{ route('repair_requests.index', ['status' => 0]) }}" class="btn btn-warning">View More</a>
                </div>
            </div>
        </div>

        <!-- Received Requests -->
        <div class="col-md-3">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    รับเรื่องแล้ว
                </div>
                <div class="card-body text-center">
                    <h3>{{ $receivedRequests }}</h3>
                    <a href="{{ route('repair_requests.index', ['status' => 1]) }}" class="btn btn-info">View More</a>
                </div>
            </div>
        </div>

        <!-- In Progress Requests -->
        <div class="col-md-3">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white">
                    กำลังดำเนินการ
                </div>
                <div class="card-body text-center">
                    <h3>{{ $inProgressRequests }}</h3>
                    <a href="{{ route('repair_requests.index', ['status' => 2]) }}" class="btn btn-primary">View More</a>
                </div>
            </div>
        </div>

        <!-- Completed Requests -->
        <div class="col-md-3 mt-3">
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    ดำเนินการแล้ว
                </div>
                <div class="card-body text-center">
                    <h3>{{ $completedRequests }}</h3>
                    <a href="{{ route('repair_requests.index', ['status' => 3]) }}" class="btn btn-success">View More</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
