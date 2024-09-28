@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">จัดการสิทธิ์</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form id="roleForm" action="{{ route('role.update') }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">ใส่Emailที่จะเปลี่ยน</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="0">Admin</option>
                        <option value="1">IT</option>
                        <option value="2">User</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">เปลี่ยน</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 confirmation before form submission
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: "คุณแน่ใจที่จะเปลี่ยนบทบาทผู้ใช้นี้ใช่ไหม!",
                icon: 'warning', // ใช้ 'warning' แทน 'เตือน'
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, เปลี่ยนมันเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();  // Submit the form if confirmed
                }
            });
        });
    </script>
</div>
@endsection
