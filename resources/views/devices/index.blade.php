@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">อุปกรณ์</h3>
            <button class="btn btn-primary" id="btnAdd">เพิ่มอุปกรณ์</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่ออุปกรณ์</th>
                        <th>ชนิดอุปกรณ์</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="deviceTable">
                    @foreach($devices as $device)
                    <tr id="device_{{ $device->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $device->Devicename }}</td>
                        <td>{{ $device->DeviceType }}</td>
                        <td>
                            <button class="btn btn-warning btnEdit" data-id="{{ $device->DeviceID }}">แก้ไข</button>
                            <button class="btn btn-danger btnDelete" data-id="{{ $device->DeviceID }}">ลบ</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Device -->
<div class="modal fade" id="deviceModal" tabindex="-1" role="dialog" aria-labelledby="deviceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deviceModalLabel">เพิ่มอุปกรณ์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deviceForm">
                    <input type="hidden" id="device_id">
                    <div class="form-group">
                        <label for="Devicename">ชื่ออุปกรณ์</label>
                        <input type="text" class="form-control" id="Devicename" required>
                    </div>
                    <div class="form-group">
                        <label for="DeviceType">ชนิดอุปกรณ์</label>
                        <input type="text" class="form-control" id="DeviceType" required>
                    </div>
                    <button type="submit" class="btn btn-success">เพิ่ม</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JQuery, Bootstrap and SweetAlert2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
    // Open Modal to Add New Device
    $('#btnAdd').click(function() {
        $('#deviceForm')[0].reset();
        $('#deviceModalLabel').text('Add Device');
        $('#device_id').val('');
        $('#deviceModal').modal('show');
    });

    $('#deviceForm').submit(function(e) {
    e.preventDefault();
    let device_id = $('#device_id').val();
    let url = device_id ? `/devices/${device_id}` : '/devices';
    let method = 'POST'; // Always use POST, with _method to simulate PUT if needed

    let data = {
        Devicename: $('#Devicename').val(),
        DeviceType: $('#DeviceType').val(),
        _token: '{{ csrf_token() }}',
        _method: device_id ? 'PUT' : 'POST' // Add _method field for PUT/POST
    };

    $.ajax({
        url: url,
        type: method,
        data: data,
        success: function(response) {
            Swal.fire('Success', response.success, 'success');
            $('#deviceModal').modal('hide');
            location.reload(); // Reload the page to update the device list
        },
        error: function(xhr) {
            console.error(xhr);
            Swal.fire('Error', 'There was an issue saving the device.', 'error');
            }
        });
    });

    // Edit Device
    $(document).on('click', '.btnEdit', function() {
        let id = $(this).data('id'); // ตรวจสอบว่า id ถูกต้องหรือไม่
        if (!id) {
            Swal.fire('Error', 'Device ID is missing!', 'error');
            return;
        }

        $.ajax({
            url: `/devices/${id}/edit`,
            type: 'GET',
            success: function(device) {
                $('#device_id').val(device.DeviceID); // ใช้ DeviceID ในการเก็บ ID
                $('#Devicename').val(device.Devicename);
                $('#DeviceType').val(device.DeviceType);
                $('#deviceModalLabel').text('Edit Device');
                $('#deviceModal').modal('show');
            },
            error: function(xhr) {
                console.error(xhr);
                Swal.fire('Error', 'There was an issue retrieving the device for editing.', 'error');
            }
        });
    });
// Delete Device
    $(document).on('click', '.btnDelete', function() {
    let id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/devices/${id}`,
                type: 'POST', // Always use POST
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE' // Add _method field for DELETE
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.success, 'success');
                    location.reload(); // Reload the page immediately after deletion
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire('Error', 'There was an issue deleting the device.', 'error');
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>
@endsection
