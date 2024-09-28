@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">บันทึก Ticket ID: {{ $request->TicketNumber }}</h5>
        </div>
        <div class="card-body">
            <form id="noteForm" action="{{ route('repair_requests.storeNote', $request->TicketID) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-control" rows="4" 
                        @if(Auth::user()->role != 1) disabled @endif>{{ old('note', $request->note) }}</textarea>
                </div>

                @if(Auth::user()->role == 1)  {{-- IT --}}
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                @else
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin') }}" class="btn btn-secondary">กลับ</a> {{-- ปุ่มกลับสำหรับ Admin --}}
                    @else
                        <a href="{{ route('repair_request.index') }}" class="btn btn-secondary">กลับ</a> {{-- ปุ่มกลับสำหรับ User --}}
                    @endif
                @endif
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('noteForm').onsubmit = function(event) {
        @if(Auth::user()->role == 1)
            event.preventDefault(); // Prevent form submission
            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                text: "คุณต้องการบันทึกหมายเหตุนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, บันทึก!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    event.target.submit();
                }
            });
        @endif
    };
</script>
@endsection
@endsection
