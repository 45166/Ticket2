<!-- resources/views/registers/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Registered Devices</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ResID</th>
                <th>TagID</th>
                <th>BrandID</th>
                <th>OS_ID</th>
                <th>MemoryID</th>
                <th>LocationID</th>
                <th>RoomID</th>
                <th>BuildID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registers as $register)
                <tr>
                    <td>{{ $register->ResID }}</td>
                    <td>{{ $register->TagID }}</td>
                    <td>{{ $register->BrandID }}</td>
                    <td>{{ $register->OS_ID }}</td>
                    <td>{{ $register->MemoryID }}</td>
                    <td>{{ $register->LocationID }}</td>
                    <td>{{ $register->RoomID }}</td>
                    <td>{{ $register->BuildID }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
