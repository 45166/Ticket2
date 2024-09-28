@if($requests->isEmpty())
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
        <tbody>
            @foreach($requests as $request)
                <tr>
                    <td class="ticket-number" 
                        data-ticket="{{ $request->TicketNumber }}" 
                        data-date="{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}"
                        data-tag="{{ $request->TagNumber }}"
                        data-repair="{{ $request->RepairDetail }}" 
                        data-device="{{ $request->device->Devicename ?? 'Unknown Device' }}"
                        data-name="{{ $request->user ? $request->user->name : 'Unknown User' }}"
                        data-status="{{ $request->status->Statusname }}"
                        data-tel="{{ $request->Tel }}" >
                        {{ $request->TicketNumber }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($request->Date)->format('d/m/Y') }}</td>
                    <td>
                        @if(!$request->TagNumber)
                            <a href="{{ route('repair_requests.showTagRegisterForm', $request->TicketID) }}" class="btn btn-success btn-sm">Register Tag</a>
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
                            <a href="{{ route('repair_requests.showNoteForm', $request->TicketID) }}" class="btn btn-warning btn-sm">เพิ่มรายละเอียด</a>
                            <a href="{{ route('add-schedule', $request->TicketID) }}" class="btn btn-success btn-sm">กำหนดเวลานัดหมาย</a>
                            <a href="{{ route('repair_requests.showStatusChangeForm', $request->TicketID) }}" class="btn btn-info btn-sm">เปลี่ยนสถานะ</a>
                           
                            
                        </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
