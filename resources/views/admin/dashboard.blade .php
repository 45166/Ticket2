@extends('layouts.app')

@section('content')
<div class="container">
    <h5>Admin Dashboard</h5>
    @if(isset($requests))
        @if($requests->isNotEmpty())
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>หมายเลขTicket </th>
                        <th>วัน/เดือน/ปี</th>
                        <th>สถานะ</th>
                        <th>อุปการณ์</th>
                        <th>มอบหมาบงานให้กับ</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->TicketID }}</td>
                            <td>{{ $request->Date }}</td>
                            <td>{{ $request->status->StatusID }}</td>
                            <td>{{ $request->device->DeviceName }}</td>
                            <td>{{ $request->assignments->first()->user->name ?? 'Not Assigned' }}</td>
                            <td>
                                <a href="{{ route('repair_requests.assign', $request->TicketID) }}" class="btn btn-primary">มอบหมาบ</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">ไม่มีการแจ้งซ่อม</div>
        @endif
    @else
        <div class="alert alert-danger">There was an error retrieving the repair requests.</div>
    @endif
</div>
@endsection
