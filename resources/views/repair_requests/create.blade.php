@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">แจ้งซ่อม</h5>
        </div>
        <div class="card-body">
            <!-- Success message display -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>สำเร็จ!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('repair_requests.store') }}" method="POST" class="mx-auto" style="max-width: 600px;">
                @csrf
                
                <div class="mb-4">
                    <label for="TagNumber" class="form-label">หมายเลข Tag:</label>
                    <small class="text-muted">*ถ้าอุปกรณ์ยังไม่ได้รับการลงทะเบียนให้เลือกไม่มี</small>
                    <select id="TagNumber" name="TagNumber" class="form-select @error('TagNumber') is-invalid @enderror">
                        <option value="">ไม่มี</option> <!-- Default option -->
                        @foreach($tagNumbers as $tagNumber)
                            <option value="{{ $tagNumber }}" {{ old('TagNumber') == $tagNumber ? 'selected' : '' }}>
                                {{ $tagNumber }}
                            </option>
                        @endforeach
                    </select>
                    @error('TagNumber')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="RepairDetail" class="form-label">อาการ:</label>
                    <textarea id="RepairDetail" name="RepairDetail" class="form-control @error('RepairDetail') is-invalid @enderror" rows="4" required>{{ old('RepairDetail') }}</textarea>
                    @error('RepairDetail')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="Device_ID" class="form-label">อุปกรณ์:</label>
                    <select id="Device_ID" name="Device_ID" class="form-select @error('Device_ID') is-invalid @enderror" required>
                        <option value="" disabled selected>เลือกอุปกรณ์</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->DeviceID }}" {{ old('Device_ID') == $device->DeviceID ? 'selected' : '' }}>
                                {{ $device->Devicename }}
                            </option>
                        @endforeach
                    </select>
                    @error('Device_ID')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="Tel" class="form-label">เบอร์โทรศัพท์:</label>
                    <input type="text" id="Tel" name="Tel" class="form-control @error('Tel') is-invalid @enderror" value="{{ old('Tel') }}" required>
                    @error('Tel')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    // Add any custom SweetAlert2 notifications here
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '{{ session('success') }}',
            confirmButtonText: 'ตกลง'
        });
    @endif
</script>
@endsection
