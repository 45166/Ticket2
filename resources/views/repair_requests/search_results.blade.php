@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title">ผลการค้นหาสำหรับแท็กหมายเลข: {{ $tagNumber }}</h5>
        </div>
        <div class="card-body">
            @if($results->isEmpty())
                <p class="text-center">ไม่พบข้อมูลการแจ้งซ่อมสำหรับหมายเลขแท็กนี้</p>
            @else
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Ticket ID</th>
                            <th scope="col">วันที่</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col">Tag Number</th>
                            <th scope="col">อุปกรณ์</th>
                            <th scope="col">รายละเอียดการแจ้งซ่อม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $request)
                            <tr>
                                <td>{{ $request->TicketNumber }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}</td>
                                <td>
                                    @switch($request->status->Statusname)
                                        @case('Pending')
                                            <span class="badge bg-warning text-dark">รอดำเนินการ</span>
                                            @break
                                        @case('รับเรื่อง')
                                            <span class="badge bg-info text-white">รับเรื่องแล้ว</span>
                                            @break
                                        @case('กำลังดำเนินการ')
                                            <span class="badge bg-primary text-white">กำลังดำเนินการ</span>
                                            @break
                                        @case('ดำเนินการแล้ว')
                                            <span class="badge bg-success text-white">ดำเนินการแล้ว</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Unknown</span>
                                    @endswitch
                                </td>
                                <td>{{ $request->TagNumber }}</td>
                                <td>{{ $request->device->Devicename ?? 'N/A' }}</td>
                                <td>{{ $request->RepairDetail }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
