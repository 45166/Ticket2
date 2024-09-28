@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">มอบหมายงาน</h5>
        </div>
        <div class="card-body">
            <!-- แสดงข้อความสำเร็จ -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>สำเร็จ!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('repair_requests.storeAssignment', $request->TicketID) }}" method="POST" class="mx-auto" style="max-width: 600px;">
                @csrf
                <div class="mb-4">
                    <label for="user_id" class="form-label">มอบหมายกับ:</label>
                    <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="" disabled selected>เลือกIT</option>
                        @foreach($itUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">มอบหมาย</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
