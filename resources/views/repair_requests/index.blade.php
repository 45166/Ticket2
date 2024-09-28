@extends('layouts.app') 

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary fw-bold">รายการแจ้งซ่อม</h1>
        <a href="{{ route('repair_requests.create') }}" class="btn btn-primary btn-lg shadow-sm">+ แจ้งซ่อมใหม่</a>
    </div>

    @if($requests->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            <strong>ยังไม่ได้แจ้งซ่อม</strong>
        </div>
    @else
        <div class="d-flex justify-content-center">
            <div class="card shadow-sm border-primary w-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">รายการแจ้งซ่อม</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Ticket ID</th>
                                    <th scope="col">วัน/เดือน/ปี</th>
                                    <th scope="col">หมายเลข Tag</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">อุปกรณ์</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td>{{ $request->TicketNumber }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('repair_requests.searchByTag', ['TagNumber' => $request->TagNumber]) }}">
                                                {{ $request->TagNumber ?? 'Not Registered' }}
                                            </a>
                                        </td>
                                        <td>
                                            @switch($request->status->Statusname)
                                                @case('Pending')
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.8rem; padding: 0.5rem 1rem;">รอดำเนินการ</span>
                                                    @break
                                                @case('รับเรื่อง')
                                                    <span class="badge bg-info text-white" style="font-size: 0.8rem; padding: 0.5rem 1rem;">รับเรื่องแล้ว</span>
                                                    @break
                                                @case('กำลังดำเนินการ')
                                                    <span class="badge bg-primary text-white" style="font-size: 0.8rem; padding: 0.5rem 1rem;">กำลังดำเนินการ</span>
                                                    @break
                                                @case('ดำเนินการแล้ว')
                                                    <span class="badge bg-success text-white" style="font-size: 0.8rem; padding: 0.5rem 1rem;">ดำเนินการแล้ว</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $request->device->Devicename ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="{{ route('repair_requests.showNote', $request->TicketID) }}" class="btn btn-info btn-sm me-2">ดูรายละเอียด</a>
                                                
                                                @if($request->status->Statusname === 'ดำเนินการแล้ว' && !$request->is_evaluated) {{-- ตรวจสอบสถานะและการประเมิน --}}
                                                    <a href="{{ route('repair_requests.evaluate', $request->TicketID) }}">
                                                        <img src="{{ asset('images/estimate.png') }}" alt="Evaluate" style="width: 30px; height: auto;">
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
