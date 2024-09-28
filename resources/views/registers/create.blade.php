<!-- resources/views/registers/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Register New Device</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('registers.store') }}" method="POST">
                @csrf

                <!-- TagID -->
                <div class="mb-3">
                    <label for="TagID" class="form-label">Tag</label>
                    <select name="TagID" class="form-select @error('TagID') is-invalid @enderror" required>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->TagID }}">{{ $tag->TagNumber }}</option>
                        @endforeach
                    </select>
                    @error('TagID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- BrandID -->
                <div class="mb-3">
                    <label for="BrandID" class="form-label">Brand ID</label>
                    <input type="text" name="BrandID" class="form-control @error('BrandID') is-invalid @enderror" value="{{ old('BrandID') }}" required>
                    @error('BrandID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- OS_ID -->
                <div class="mb-3">
                    <label for="OS_ID" class="form-label">Operating System ID</label>
                    <input type="text" name="OS_ID" class="form-control @error('OS_ID') is-invalid @enderror" value="{{ old('OS_ID') }}" required>
                    @error('OS_ID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- MemoryID -->
                <div class="mb-3">
                    <label for="MemoryID" class="form-label">Memory ID</label>
                    <input type="text" name="MemoryID" class="form-control @error('MemoryID') is-invalid @enderror" value="{{ old('MemoryID') }}" required>
                    @error('MemoryID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- LocationID -->
                <div class="mb-3">
                    <label for="LocationID" class="form-label">Location ID</label>
                    <input type="text" name="LocationID" class="form-control @error('LocationID') is-invalid @enderror" value="{{ old('LocationID') }}" required>
                    @error('LocationID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- RoomID -->
                <div class="mb-3">
                    <label for="RoomID" class="form-label">Room ID</label>
                    <input type="text" name="RoomID" class="form-control @error('RoomID') is-invalid @enderror" value="{{ old('RoomID') }}" required>
                    @error('RoomID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- BuildID -->
                <div class="mb-3">
                    <label for="BuildID" class="form-label">Building ID</label>
                    <input type="text" name="BuildID" class="form-control @error('BuildID') is-invalid @enderror" value="{{ old('Build

ID') }}" required>
                    @error('BuildID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Register Device</button>
            </form>
        </div>
    </div>
</div>
@endsection
