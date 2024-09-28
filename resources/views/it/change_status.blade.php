@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Change Status for Ticket ID: {{ $request->TicketID }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('repair_requests.updateStatus', $request->TicketID) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="status_id" class="form-label">Status</label>
                    <select name="status_id" id="status_id" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status->StatusID }}" {{ $request->StatusID == $status->StatusID ? 'selected' : '' }}>
                                {{ $status->Statusname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Add a text area for the note -->
              
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
