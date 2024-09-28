@extends('layouts.app')

@section('content')
<div class="container">
    

    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <h5 class="">หน้ามอบหมายงาน</h5>
        <form id="statusFilterForm" class="d-flex align-items-center">
            <label for="statusFilter" class="form-label  me-2 mb-0">Filter by Status:</label>
            <select id="statusFilter" class="form-select" aria-label="Filter by Status">
                <option value="">All</option>
                <option value="Pending">รอดำเนินการ</option>
                <option value="รับเรื่อง">รับเรื่องแล้ว</option>
                <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>
                <option value="ดำเนินการแล้ว">ดำเนินการแล้ว</option>
            </select>
           
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>หมายเลขTag</th>
                    <th>วัน/เดือน/ปี</th>
                    <th>สถานะ</th>
                    <th>อุปกรณ์</th>
                    <th>ผู้รับผิดชอบงาน</th>
                    <th>ดูรายละเอียด</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="requestTableBody">
                @foreach($requests as $request)
                    <tr data-status="{{ $request->status->Statusname }}">
                        <td>
                            <a href="#" class="ticket-number" data-ticket="{{ $request->TicketNumber }}" 
                                data-ticket="{{ $request->TicketNumber }}" 
                                data-tag="{{ $request->TagNumber }}" 
                                data-date="{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}" 
                                data-repair="{{ $request->RepairDetail }}" 
                                data-device="{{ $request->device->Devicename }}" 
                                data-status="{{ $request->status->Statusname }}"
                                data-name="{{ $request->user ? $request->user->name : 'Unknown User' }}"
                                data-tel="{{ $request->Tel }}" >
                                {{ $request->TicketNumber }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('repair_requests.searchByTag', ['TagNumber' => $request->TagNumber]) }}">
                                {{ $request->TagNumber ?? 'ยังไม่ลงทะเบียน' }}
                            </a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}</td>
                        <td>
                            @switch($request->status->Statusname)
                            @case('Pending')
                                <span class="badge bg-warning text-dark" style="font-size: 0.8rem; padding: 0.5rem 1rem;">รอดำเนินการ</span>
                                @break
                            @case('รับเรื่อง')
                                <span class="badge bg-info text-white" style="font-size: 0.8rem; padding: 0.5rem 1rem;">รับเรื่องแล้ว</span>
                                @break
                            @case('กำลังดำเนินการ')
                                <span class="badge bg-primary text-white" style="font-size: 0.8rem; padding: 0.5rem 1rem;">กำลังดำเนินการ</span>
                                @break
                            @case('ดำเนินการแล้ว')
                                <span class="badge bg-success text-white" style="font-size: 0.8rem; padding: 0.5rem 1rem;">ดำเนินการแล้ว</span>
                                @break
                            @default
                                <span class="badge bg-secondary" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Unknown</span>
                            @endswitch
                        </td>
                        <td>{{ $request->device->Devicename }}</td>
                        <td>{{ $request->assignments->first()->user->name ?? 'Not Assigned' }}</td>
                        <td>
                            <a href="{{ route('repair_requests.showNote', $request->TicketID) }}" class="btn btn-info btn-sm">ดูรายละเอียด</a>
                        </td>
                        <td>
                            @if(!$request->assignments->isEmpty())
                                <span class="">มอบหมายแล้ว</span>
                            @else
                                <a href="{{ route('repair_requests.assign', $request->TicketID) }}" class="btn btn-primary">มอบหมายงาน</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- เพิ่ม Modal -->
<div class="modal fade" id="repairDetailsModal" tabindex="-1" role="dialog" aria-labelledby="repairDetailsModalLabel" aria-hidden="true">
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
                <p><strong>วันเดือนปี:</strong> <span id="modal-date"></span></p>
                <p><strong>หมายเลข Tag:</strong> <span id="modal-tag"></span></p>
                <p><strong>อาการ:</strong> <span id="modal-repair"></span></p>
                <p><strong>อุปกรณ์:</strong> <span id="modal-device"></span></p>
                <p><strong>สถานะ:</strong> <span id="modal-status"></span></p>
                <p><strong>ชื่อผู้แจ้ง:</strong> <span id="modal-name"></span></p>
                <p><strong>เบอร์โทรศัพ:</strong> <span id="modal-tel"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- เพิ่ม jQuery และ Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- เพิ่ม JavaScript/jQuery -->
<script>
    $(document).ready(function() {
        // ฟังก์ชันสำหรับกรองสถานะ
        $('#statusFilter').on('change', function() {
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

        $('.ticket-number').on('click', function(event) {
            event.preventDefault();

            // ดึงข้อมูลจาก data-* attributes
            var ticket = $(this).data('ticket');
            var date = $(this).data('date');
            var tag = $(this).data('tag');
            var repair = $(this).data('repair');
            var device = $(this).data('device');
            var tel = $(this).data('tel');
            var name = $(this).data('name');
            var status = $(this).data('status');

            // นำข้อมูลไปใส่ใน modal
            $('#modal-ticket').text(ticket);
            $('#modal-date').text(date);
            $('#modal-tag').text(tag);
            $('#modal-repair').text(repair);
            $('#modal-device').text(device);
            $('#modal-tel').text(tel);
            $('#modal-name').text(name);
            $('#modal-status').text(status);

            // แสดง modal
            $('#repairDetailsModal').modal('show');
        });
    });
</script>

@endsection
