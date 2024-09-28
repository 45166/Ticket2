@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title">Register Tag Number for Ticket #{{ $repairRequest->TicketNumber }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('repair_requests.storeTag', $repairRequest->TicketID) }}" method="POST">
                @csrf

                <!-- Automatically generated TagNumber -->
                <div class="mb-3">
                    <label for="TagNumber" class="form-label">Tag Number</label>
                    <input type="text" name="TagNumber" class="form-control" id="TagNumber" value="{{ $generatedTagNumber }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="EquipmentNumber" class="form-label">หมายเลขครุภัณฑ์</label>
                    <input type="text" name="EquipmentNumber" class="form-control" id="EquipmentNumber" placeholder="ใส่เลขครุภัณฑ์" required>
                </div>

                <div class="mb-3">
                    <label for="features" class="form-label">คุณสมบัติ</label>
                    <textarea name="features" class="form-control" id="features" placeholder="ใส่คุณสมบัติ" rows="5" cols="50" required></textarea>
                </div>

<!-- Dropdown สำหรับเลือกตึก -->
<div class="mb-3">
    <label for="building_id" class="form-label">ตึก</label>
    <select name="building_id" id="building_id" class="form-control" required>
        <option value="">เลือกตึก</option>
        @foreach($buildings as $building)
            <option value="{{ $building->id }}" data-floors="{{ $building->floor }}">{{ $building->building }}</option> <!-- เปลี่ยนจาก $building->name เป็น $building->building -->
        @endforeach
    </select>
</div>

                <!-- Dropdown สำหรับเลือกชั้น -->
                <div class="mb-3">
                    <label for="floor" class="form-label">ชั้น</label>
                    <select name="floor" id="floor" class="form-control" required>
                        <option value="">เลือกชั้น</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="room" class="form-label">ห้อง</label>
                    <input type="text" name="room" class="form-control" id="room" placeholder="ห้อง" required>
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">คณะ/สำนัก/สถาบัน</label>
                    <input type="text" name="department" class="form-control" id="department" placeholder="คณะ/สำนัก/สถาบัน" required>
                </div>

                <button type="submit" class="btn btn-success">Register Tag</button>
            </form>
        </div>
    </div>
</div>

<script>
    // เมื่อเลือกตึก จะอัปเดตจำนวนชั้นตามที่มีในตึกนั้นๆ
    document.getElementById('building_id').addEventListener('change', function() {
        var selectedBuilding = this.options[this.selectedIndex];
        var floors = selectedBuilding.getAttribute('data-floors'); // ดึงจำนวนชั้นจาก attribute
        var floorSelect = document.getElementById('floor');

        // ล้างค่าใน dropdown ของชั้น
        floorSelect.innerHTML = '<option value="">เลือกชั้น</option>';

        // เพิ่มตัวเลือกชั้นตามจำนวนชั้นของตึก
        for (var i = 1; i <= floors; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.text = 'ชั้น ' + i;
            floorSelect.appendChild(option);
        }
    });
</script>

@endsection
