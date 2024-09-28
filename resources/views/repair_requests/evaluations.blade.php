@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('evaluations.export') }}" class="btn btn-success">
            ดาวน์โหลดผลการประเมินเป็น Excel
        </a>
    </div>
    <div class="card shadow-sm border-primary">
        
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">ผลแบบประเมิน</h5>
        </div>
        
        
        <div class="card-body">
            @if($evaluations->isEmpty())
                <p class="text-center">No evaluations available.</p>
            @else
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>วัน/เดือน/ปี</th>
                            <th>ผู้แจ้งซ่อม</th>
                            <th>ความพึงพอใจ</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluations as $evaluation)
                            <tr>
                                <td>{{ $evaluation->repairRequest->TicketNumber }}</td>
                                <td>{{ $evaluation->repairRequest->created_at->format('Y-m-d') }}</td>
                                <td>{{ $evaluation->user->name }}</td>
                                <td>
                                    @if($evaluation->rating == 1)
                                        <img src="/images/unhappy.png" alt="Unhappy" style="max-width: 30px;">
                                    @elseif($evaluation->rating == 2)
                                        <img src="/images/neutral.png" alt="Neutral" style="max-width: 30px;">
                                    @elseif($evaluation->rating == 3)
                                        <img src="/images/smile.png" alt="Smile" style="max-width: 30px;">
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
