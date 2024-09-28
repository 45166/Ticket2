@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-center">
            <div class="card shadow-sm border-primary w-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">รายการแจ้งซ่อม</h5>
                    <form id="statusFilterForm" class="d-inline">
                        <div class="form-group mb-0">
                            <label for="status" class="form-label text-white me-2">เลือกสถานะ:</label>
                            <select class="form-control d-inline-block w-auto" id="status" name="status">
                                <option value="">All</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>รอดำเนินการ
                                </option>
                                <option value="รับเรื่อง" {{ request('status') == 'รับเรื่อง' ? 'selected' : '' }}>
                                    รับเรื่องแล้ว</option>
                                <option value="กำลังดำเนินการ"
                                    {{ request('status') == 'กำลังดำเนินการ' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                                <option value="ดำเนินการแล้ว" {{ request('status') == 'ดำเนินการแล้ว' ? 'selected' : '' }}>
                                    ดำเนินการแล้ว</option>
                            </select>
                        </div>
                    </form>

                </div>

                <div class="card-body">
                    <div id="repairRequestTable">
                        @if ($requests->isEmpty())
                            <div class="alert alert-warning text-center" role="alert">
                                <strong>No repair requests found.</strong>
                            </div>
                        @else
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Ticket ID</th>
                                        <th scope="col">วัน/เดือน/เวลา</th>
                                        <th scope="col">หมายเลข Tag</th>
                                        <th scope="col">สถานะ</th>
                                        <th scope="col">อุปกรณ์</th>
                                        <th scope="col">เบอร์โทรศัพท์</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="requestTableBody">
                                    @foreach ($requests as $request)
                                        <tr data-status="{{ $request->status->Statusname ?? 'Unknown' }}">
                                            <td class="ticket-number" data-ticket="{{ $request->TicketNumber }}"
                                                data-date="{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}"
                                                data-tag="{{ $request->TagNumber }}"
                                                data-repair="{{ $request->RepairDetail }}"
                                                data-device="{{ $request->device->Devicename ?? 'Unknown Device' }}"
                                                data-name="{{ $request->user ? $request->user->name : 'Unknown User' }}"
                                                data-status="{{ $request->status->Statusname }}"
                                                data-tel="{{ $request->Tel }}">
                                                {{ $request->TicketNumber }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if (!$request->TagNumber)
                                                    <a href="{{ route('repair_requests.showTagRegisterForm', $request->TicketID) }}"
                                                        class="btn btn-success btn-sm">Register Tag</a>
                                                @else
                                                    {{ $request->TagNumber }}
                                                    <a href="{{ route('it', ['TagNumber' => $request->TagNumber]) }}" </a>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($request->status->Statusname ?? 'Unknown')
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
                                                        <span class="badge bg-secondary text-white">Unknown</span>
                                                @endswitch
                                            </td>

                                            <td>{{ $request->device->Devicename ?? 'Unknown Device' }}</td>

                                            <td>{{ $request->Tel }}</td>
                                            <td>
                                                <a href="{{ route('repair_requests.showNoteForm', $request->TicketID) }}"
                                                    class="btn btn-warning btn-sm">เพิ่มรายละเอียด</a>
                                                <a href="{{ route('add-schedule', $request->TicketID) }}"
                                                    class="btn btn-success btn-sm">กำหนดเวลานัดหมาย</a>
                                                <a href="{{ route('repair_requests.showStatusChangeForm', $request->TicketID) }}"
                                                    class="btn btn-info btn-sm">เปลี่ยนสถานะ</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="repairDetailsModal" tabindex="-1" role="dialog" aria-labelledby="repairDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="repairDetailsModalLabel">Repair Request Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>TicketID:</strong> <span id="modal-ticket"></span></p>
                    <p><strong>วัน/เดือน/ปี:</strong> <span id="modal-date"></span></p>
                    <p><strong>หมายเลย Tag:</strong> <span id="modal-tag"></span></p>
                    <p><strong>อาการ:</strong> <span id="modal-repair"></span></p>
                    <p><strong>อุปกรณ์:</strong> <span id="modal-device"></span></p>
                    <p><strong>สถานะ:</strong> <span id="modal-status"></span></p>
                    <p><strong>ชื่อผู้แจ้ง:</strong> <span id="modal-name"></span></p>
                    <p><strong>เบอร์โทรศัพ:</strong> <span id="modal-tel"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
    // Filter based on status dynamically
    $('#status').on('change', function() {
        var selectedStatus = $(this).val();
        $('#requestTableBody tr').each(function() {
            var status = $(this).data('status');
            if (selectedStatus === '' || status === selectedStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Handle modal popup when clicking ticket number
    $('.ticket-number').on('click', function(event) {
        event.preventDefault();

        // Get data from attributes
        var ticket = $(this).data('ticket');
        var date = $(this).data('date');
        var tag = $(this).data('tag');
        var repair = $(this).data('repair');
        var device = $(this).data('device');
        var tel = $(this).data('tel');
        var name = $(this).data('name');
        var status = $(this).data('status');

        // Insert data into modal
        $('#modal-ticket').text(ticket);
        $('#modal-date').text(date);
        $('#modal-tag').text(tag);
        $('#modal-repair').text(repair);
        $('#modal-device').text(device);
        $('#modal-tel').text(tel);
        $('#modal-name').text(name);
        $('#modal-status').text(status);

        // Show modal
        $('#repairDetailsModal').modal('show');
    });
});

    </script>
@endsection
