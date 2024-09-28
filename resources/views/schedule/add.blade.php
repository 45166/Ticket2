@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ URL('/create-schedule') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <!-- Display the Ticket Number instead of User's name -->
            <div class="form-group">
                <label for="ticket_number">{{ __('Ticket Number') }}</label>
                <input type="text" class="form-control" id="ticket_number" name="ticket_number" value="{{ $ticket->TicketNumber }}" disabled>
            </div>

            <div class="form-group">
                <label for="title">{{ __('Title') }}</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $ticket->TicketNumber }}'s Schedule">
            </div>

            <div class="form-group">
                <label for="start">{{ __('Start') }}</label>
                <input type="datetime-local" class="form-control" id="start" name="start" required value="{{ now()->format('Y-m-d\TH:i') }}">
            </div>

            <div class="form-group">
                <label for="end">{{ __('End') }}</label>
                <input type="datetime-local" class="form-control" id="end" name="end" required value="{{ now()->format('Y-m-d\TH:i') }}">
            </div>

            <div class="form-group">
                <label for="description">{{ __('Description') }}</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="color">{{ __('Color') }}</label>
                <input type="color" id="color" name="color" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
        </form>
    </div>
@endsection
