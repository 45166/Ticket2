@extends('layouts.app')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Schedule Tracker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6"></div>
        </div>

        <div class="col-md-6">
            <div class="btn-group mb-3" role="group" aria-label="Calendar Actions">
                <button id="exportButton" class="btn btn-success">{{__('Export Calendar')}}</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="calendar" style="width: 100%;height:100vh"></div>
            </div>
        </div>

        <!-- Modal to show event details -->
        <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventDetailModalLabel">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Title:</strong> <span id="eventTitle"></span></p>
                        <p><strong>Start:</strong> <span id="eventStart"></span></p>
                        <p><strong>End:</strong> <span id="eventEnd"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button id="deleteEventButton" class="btn btn-danger">Delete Event</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script type="text/javascript">
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            events: '/events',
            editable: true,

            eventClick: function(info) {
                document.getElementById('eventTitle').textContent = info.event.title;
                document.getElementById('eventStart').textContent = info.event.start.toLocaleString();
                document.getElementById('eventEnd').textContent = info.event.end ? info.event.end.toLocaleString() : 'No end time';

                document.getElementById('deleteEventButton').setAttribute('data-event-id', info.event.id);
                
                var eventModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                eventModal.show();
            },

            eventDrop: function(info) {
                var eventId = info.event.id;
                var newStartDate = info.event.start;
                var newEndDate = info.event.end || newStartDate;
                var newStartDateUTC = newStartDate.toISOString().slice(0, 19).replace('T', ' ');
                var newEndDateUTC = newEndDate.toISOString().slice(0, 19).replace('T', ' ');

                fetch(`/schedule/${eventId}`, {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        start_date: newStartDateUTC,
                        end_date: newEndDateUTC
                    })
                })
                .then(response => response.json())
                .then(data => console.log('Event moved successfully.'))
                .catch(error => console.error('Error moving event:', error));
            },

            eventResize: function(info) {
                var eventId = info.event.id;
                var newEndDate = info.event.end;
                var newEndDateUTC = newEndDate.toISOString().slice(0, 19).replace('T', ' ');

                fetch(`/schedule/${eventId}/resize`, {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        end_date: newEndDateUTC
                    })
                })
                .then(response => response.json())
                .then(data => console.log('Event resized successfully.'))
                .catch(error => console.error('Error resizing event:', error));
            }
        });

        calendar.render();

        document.getElementById('deleteEventButton').addEventListener('click', function() {
            var eventId = this.getAttribute('data-event-id');
            fetch(`/schedule/delete/${eventId}`)
                .then(response => response.json())
                .then(data => {
                    calendar.getEventById(eventId).remove();
                    console.log('Event deleted successfully.');
                })
                .catch(error => console.error('Error deleting event:', error));
        });
    </script>
@endsection
