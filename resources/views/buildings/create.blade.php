@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <h3 class="card-title">เพิ่มอาคาร</h3>
        </div>
        <div class="card-body">
            <!-- แสดงข้อความเมื่อบันทึกสำเร็จ -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- ฟอร์มสำหรับกรอกข้อมูล -->
            <form action="{{ route('buildings.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="building" class="form-label">ชื่ออาคาร:</label>
                    <input type="text" name="building" id="building" class="form-control" value="{{ old('building') }}" required>
                    @error('building')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="floor" class="form-label">จำนวนชั้น:</label>
                    <input type="number" name="floor" id="floor" class="form-control" value="{{ old('floor') }}" required>
                    @error('floor')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">เพิ่มอาคาร</button>
            </form>
        </div>
    </div>
</div>

<!-- JQuery, Bootstrap and SweetAlert2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    // If there's a success message, show a SweetAlert notification
    @if(session('success'))
        Swal.fire({
            title: 'สำเร็จ!',
            text: '{{ session("success") }}',
            icon: 'success',
            confirmButtonText: 'ตกลง'
        });
    @endif
</script>
@endsection
