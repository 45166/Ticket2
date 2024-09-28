@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Repair Request Report</h1>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Total Requests: {{ $totalRequests }}</h3>
            <ul>
                <li>Pending: {{ $pendingRequests }}</li>
                <li>Received: {{ $receivedRequests }}</li>
                <li>In Progress: {{ $inProgressRequests }}</li>
                <li>Completed: {{ $completedRequests }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <canvas id="statusPieChart"></canvas>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <canvas id="ratingBarChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart for Statuses
    const ctxStatus = document.getElementById('statusPieChart').getContext('2d');
    const statusPieChart = new Chart(ctxStatus, {
        type: 'pie',
        data: {
            labels: {!! json_encode($statuses->values()) !!},
            datasets: [{
                label: 'Requests by Status',
                data: {!! json_encode(array_values($statusData)) !!},
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            }
        }
    });

    // Bar Chart for Ratings
    const ctxRating = document.getElementById('ratingBarChart').getContext('2d');
    const ratingBarChart = new Chart(ctxRating, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ratings) !!},
            datasets: [{
                label: 'Requests by Rating',
                data: {!! json_encode(array_values($ratingData)) !!},
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
</script>
@endsection
