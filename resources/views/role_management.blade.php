@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Role Management</h1>
    
    <form action="{{ route('role.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">User Email:</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter user's email" required>
        </div>

        <div class="form-group">
            <label for="role">Select Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="0">Admin</option>
                <option value="1">IT</option>
                <option value="2">User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Role</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection
