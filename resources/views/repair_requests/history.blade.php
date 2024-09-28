<!-- resources/views/repair_requests/history.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if($repairRequests->isEmpty())
        <div class="alert alert-warning text-center">
            No repair history found for Tag Number: {{ $tagNumber }}
        </div>
    @else
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title">Repair History for Tag Number: {{ $tagNumber }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Repair Detail</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repairRequests as $request)
                            <tr>
                                <td>{{ $request->TicketNumber }}</td>
                                <td>{{ $request->RepairDetail }}</td>
                                <td>{{ $request->status->Statusname }}</td>
                                <td>{{ $request->Date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- ปุ่มแจ้งซ่อมอีกครั้ง -->
                <div class="mt-3">
                    <a href="{{ route('repair_requests.create', ['TagNumber' => $tagNumber]) }}" class="btn btn-primary">
                        แจ้งซ่อมอีกครั้ง
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
