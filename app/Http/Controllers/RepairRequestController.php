<?php
namespace App\Http\Controllers;
use App\Models\{RepairRequest, Building, Device, RegisterTag, Assignment, User, Status, Evaluation};
use Illuminate\Support\Facades\{Auth, DB, Log, Http};
use Illuminate\Http\Request;
class RepairRequestController extends Controller
{
    // ฟังก์ชันสำหรับแสดงรายการแจ้งซ่อมสำหรับ Admin
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $status = $request->input('status'); // รับค่าจาก URL
            
            // Log ค่าของ status เพื่อตรวจสอบ
            Log::info('Current Status: ' . $status);
            
            // สร้าง Query
            $query = RepairRequest::with('status', 'device');
            
            // ตรวจสอบสิทธิ์ของผู้ใช้
            if ($user->role == 0) { // Admin
                // Admin: แสดงคำขอทั้งหมด
            } elseif ($user->role == 1) { // IT Staff
                // IT Staff: กรองตามการมอบหมาย
                $query->whereHas('assignments', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            } else { // Regular User
                // Regular User: แสดงเฉพาะคำขอที่ผู้ใช้สร้างเอง
                $query->where('user_id', $user->id);
            }
            
            // ถ้ามีการระบุสถานะใน URL จะกรองตามสถานะ
            if ($status !== null) {
                $query->where('StatusID', $status);
            }
            $requests = RepairRequest::with('user')->get();
            // ดึงข้อมูลโดยใช้ pagination
            $requests = $query->paginate(10);
            
            return view('repair_requests.index', compact('requests'));
        } catch (\Exception $e) {
            Log::error('Error retrieving repair requests: ' . $e->getMessage());
            return view('repair_requests.index')->with('error', 'There was an error retrieving the repair requests.');
        }
    }
    public function dashboard()
    {
        $userId = Auth::id(); // ดึง user_id ของผู้ใช้ที่ล็อกอินอยู่
    
        $totalRequests = RepairRequest::where('user_id', $userId)->count(); // จำนวนงานแจ้งซ่อมทั้งหมดของผู้ใช้
        $pendingRequests = RepairRequest::where('user_id', $userId)->where('StatusID', 0)->count(); // Pending
        $receivedRequests = RepairRequest::where('user_id', $userId)->where('StatusID', 1)->count(); // รับเรื่องแล้ว
        $inProgressRequests = RepairRequest::where('user_id', $userId)->where('StatusID', 2)->count(); // กำลังดำเนินการ
        $completedRequests = RepairRequest::where('user_id', $userId)->where('StatusID', 3)->count(); // ดำเนินการแล้ว
    
        // ส่งค่าตัวแปรทั้งหมดไปที่ view dashboard
        return view('dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'receivedRequests',
            'inProgressRequests',
            'completedRequests'
        ));
    }
    

    // ฟังก์ชันสำหรับแสดง Dashboard ของ Admin
    public function adminDashboard()
    {
        try {
            $user = Auth::user();
            
            // ตรวจสอบว่าเป็น admin
            if ($user->role == 0) { // Admin
                $requests = RepairRequest::with(['status', 'device', 'assignments.user'])->get();
            }

            // ส่งข้อมูลไปยังหน้า admin.blade.php
            return view('admin', compact('requests'));

        } catch (\Exception $e) {
            Log::error('Error retrieving repair requests: ' . $e->getMessage());
            return view('admin')->with('error', 'There was an error retrieving the repair requests.');
        }
    }
    // ฟังก์ชันสำหรับ Admin มอบหมายงาน
    public function assign($id)
    {
        $request = RepairRequest::findOrFail($id); // ดึงข้อมูลการแจ้งซ่อมจาก ID
        $itUsers = User::where('role', 1)->get(); // ดึงรายชื่อ IT ทั้งหมด
        return view('repair_requests.assign', compact('request', 'itUsers'));
    }
    // ฟังก์ชันสำหรับบันทึกการมอบหมายงาน
    public function storeAssignment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Assignment::create([
            'TicketID' => $id,
            'user_id' => $validatedData['user_id'],
        ]);

        return redirect()->route('admin')->with('success', 'Repair Request Assigned Successfully');

    }

    // ฟังก์ชันสำหรับแสดงรายการแจ้งซ่อมสำหรับ IT
    public function itIndex()
    {
        $requests = RepairRequest::whereHas('assignments', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('status', 'device')->get();

        return view('it', compact('requests'));
    }
    function sendLineNotify($message)
{
    $token = 'GdF5Un5Lh3YTqPPUhZwkJd9wcURvuFEpsS1tx57rlGh'; // เปลี่ยนเป็น Access Token ที่ได้จาก LINE Notify
    $url = 'https://notify-api.line.me/api/notify';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->asForm()->post($url, [
        'message' => $message,
    ]);

    if ($response->successful()) {
        Log::info('LINE Notify sent successfully.');
    } else {
        Log::error('Error sending LINE Notify: ' . $response->body());
    }
}
    public function userIndex()
    {
        $requests = RepairRequest::where('user_id', Auth::id())->with('status', 'device')->get();
        return view('dashboard', compact('requests'));
    }

    // ฟังก์ชันสำหรับสร้างรายการแจ้งซ่อมใหม่
    public function create(Request $request)
    {
        // Get the tag number from the input
        $tagNumber = $request->input('TagNumber');
        
        // Get user_id of the logged-in user
        $userId = Auth::id(); 
    
        // Get tag numbers that the user has registered
        $tagNumbers = DB::table('register_tags as rt')
            ->join('repair_requests as rr', 'rt.repair_request_id', '=', 'rr.TicketID')
            ->where('rr.user_id', $userId)
            ->pluck('rt.TagNumber')
            ->toArray(); 
        
        // Get device information based on the selected tag number
        $deviceInfo = null;
        if ($tagNumber) {
            $repairRequest = RepairRequest::where('TagNumber', $tagNumber)->with('device')->first();
            if ($repairRequest) {
                $deviceInfo = $repairRequest->device; // Get device information
            }
        }
    
        // Fetch all devices for the dropdown
        $devices = Device::all(); 
    
        // Send data to view
        return view('repair_requests.create', [
            'devices' => $devices,
            'tagNumbers' => $tagNumbers,
            'tagNumber' => $tagNumber,
            'deviceInfo' => $deviceInfo, // Pass device information to the view
        ]);
    }
    public function getRepairDetails(Request $request)
{
    $tagNumber = $request->input('tag_number');
    $repairRequest = RepairRequest::where('TagNumber', $tagNumber)->first(); // ดึงข้อมูลการซ่อมเก่าที่เกี่ยวข้อง

    return response()->json($repairRequest);
}
    // ฟังก์ชันสำหรับบันทึกรายการแจ้งซ่อมใหม่
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'TagNumber' => 'nullable|string|max:30', // ต้องเป็น nullable
                'RepairDetail' => 'required|string|max:255',
                'Device_ID' => 'required|exists:devices,DeviceID',
                'Tel' => 'required|digits:10',
            ]);
    
            // ดึง StatusID ของ 'Pending'
            $status = Status::where('Statusname', 'Pending')->first();
    
            if (!$status) {
                return back()->withErrors(['error' => 'Status "Pending" not found.']);
            }
    
            $repairRequest = RepairRequest::create([
                'Date' => now(), // วันที่ปัจจุบัน
                'TagNumber' => $validatedData['TagNumber'],
                'RepairDetail' => $validatedData['RepairDetail'],
                'Device_ID' => $validatedData['Device_ID'],
                'Tel' => $validatedData['Tel'],
                'StatusID' => $status->StatusID, // ใช้ StatusID ของ 'Pending'
                'user_id' => Auth::id(),
            ]);
    
            // ตรวจสอบค่า TicketNumber
            Log::info('Created RepairRequest with TicketNumber: ' . $repairRequest->TicketNumber);
    
            // ส่งแจ้งเตือนทาง LINE Notify
            $message = "ผู้ใช้ได้ทำการแจ้งซ่อม หมายเลข TicketID: " . $repairRequest->TicketNumber;
            $this->sendLineNotify($message);
    
            return redirect()->route('dashboard')->with('success', 'Repair request created successfully');
        } catch (\Exception $e) {
            // แสดงข้อผิดพลาด
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function searchByTag(Request $request)
    {
        $tagNumber = $request->input('TagNumber');
    
        // ค้นหาข้อมูลการแจ้งซ่อมที่เกี่ยวข้องกับ TagNumber
        $results = RepairRequest::where('TagNumber', $tagNumber)
            ->with('status', 'device') // ดึงข้อมูล status และ device
            ->get();
    
        // ตรวจสอบว่ามีผลลัพธ์หรือไม่
        if ($results->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'ไม่พบข้อมูลการแจ้งซ่อมสำหรับหมายเลขแท็กนี้');
        }
    
        return view('repair_requests.search', compact('results', 'tagNumber'));
    }
    
    public function showSearchForm()
    {
        return view('repair_requests.search');
    }
    public function generateTagNumber($deviceType)
    {
        // Fetch the last TagNumber based on the device type (e.g., N for a specific device type)
        $lastTag = RegisterTag::where('TagNumber', 'like', $deviceType . '%')
            ->orderBy('TagNumber', 'desc')
            ->first();

        // Increment the last number or start with 1 if there are no tags
        $newNumber = $lastTag ? (intval(substr($lastTag->TagNumber, strlen($deviceType))) + 1) : 1;

        return sprintf('%s%04d', $deviceType, $newNumber); // Format to N0001, N0002, etc.
    }

    public function showRegisterTagForm($ticketID)
    {
        // ดึงข้อมูล repair request ตาม ticketID
        $repairRequest = RepairRequest::findOrFail($ticketID);
    
        // ดึงตัวอักษรแรกจาก DeviceType และสร้าง Tag Number
        $deviceType = strtoupper(substr($repairRequest->device->DeviceType, 0, 1)); // รับเฉพาะตัวแรก
        $generatedTagNumber = $this->generateTagNumber($deviceType);
    
        // ดึงข้อมูลตึกทั้งหมดจากตาราง buildings
        $buildings = Building::all();
    
        // ตรวจสอบข้อมูลที่ได้จาก buildings
       
    
        return view('repair_requests.register_tag', compact('repairRequest', 'generatedTagNumber', 'buildings'));
    }
    

    public function storeTag(Request $request, $repair_request_id)
{
    
    try {
        $validatedData = $request->validate([
            'TagNumber' => 'required|string|max:30',
            'EquipmentNumber' => 'required|string|max:255',
            'features' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id', // ตรวจสอบว่ามี building_id
            'floor' => 'required|integer', // ตรวจสอบว่ามี floor
        ]);

        // บันทึกข้อมูลในตาราง register_tags
        $registerTag = RegisterTag::create([
            'repair_request_id' => $repair_request_id,
            'TagNumber' => $validatedData['TagNumber'],
            'EquipmentNumber' => $validatedData['EquipmentNumber'],
            'features' => $validatedData['features'],
            'room' => $validatedData['room'],
            'department' => $validatedData['department'],
            'building_id' => $validatedData['building_id'], // บันทึก building_id
            'floor' => $validatedData['floor'], // บันทึก floor
        ]);

        // อัปเดตฟิลด์ TagNumber ในตาราง repair_requests
        $repairRequest = RepairRequest::findOrFail($repair_request_id);
        $repairRequest->TagNumber = $validatedData['TagNumber'];
        $repairRequest->save();
        
        return redirect()->route('it', $repair_request_id)->with('success', 'Tag registered and updated successfully.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
    public function registerTag(Request $request, $id)
    {
        $validatedData = $request->validate([
            'TagNumber' => 'required|string|max:30',
        ]);

        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->update([
            'TagNumber' => $validatedData['TagNumber'],
        ]);

        return redirect()->route('it')->with('success', 'ลงทะเบียนแท็กสำเร็จ');
    }

        
    public function showStatusChangeForm($id)
    {
        $request = RepairRequest::findOrFail($id);
        $statuses = Status::all();
        
        return view('it.change_status', compact('request', 'statuses'));
    }
    
    
    // ฟังก์ชันสำหรับบันทึกการเปลี่ยนสถานะ
    public function updateStatus(Request $request, $id)
    {
        // Validate the input, including the note
        $validatedData = $request->validate([
            'status_id' => 'required|exists:statuses,StatusID',
          
        ]);
    
        $repairRequest = RepairRequest::findOrFail($id);
        
        // Update the status and the note
        $repairRequest->update([
            'StatusID' => $validatedData['status_id'],
            
        ]);
    
        // Send notification via LINE Notify
        $status = Status::where('StatusID', $validatedData['status_id'])->first();
        $message = "IT ได้เปลี่ยนสถานะของหมายเลข TicketID: " . $repairRequest->TicketNumber . " เป็น " . $status->Statusname;
        $this->sendLineNotify($message);
    
        return redirect()->route('it')->with('success', 'Repair request status updated successfully');
    }
    public function filterByStatus(Request $request)
{
    $status = $request->get('status');

    $query = RepairRequest::query();

    if ($status) {
        $query->whereHas('status', function ($q) use ($status) {
            $q->where('Statusname', $status);
        });
    }

    $requests = $query->get();

    // ส่งข้อมูลตารางกลับมา
    return view('repair_requests.partials._table', compact('requests'));
}
    // In RepairRequestController.php

public function showNoteForm($id)
{
    $request = RepairRequest::findOrFail($id);
    return view('repair_requests.note', compact('request'));
}
public function showNote($id)
{
    $request = RepairRequest::findOrFail($id);
    return view('repair_requests.note', compact('request'));
}

public function storeNote(Request $request, $id)
{
    // ตรวจสอบว่าผู้ใช้ที่ล็อกอินอยู่เป็น IT หรือไม่
    if (Auth::user()->role != 1) {
        return redirect()->back()->with('error', 'You do not have permission to add notes.');
    }

    // อนุญาตให้หมายเหตุเป็นค่าว่าง
    $validatedData = $request->validate([
        'note' => 'nullable|string|max:255',  // ใช้ nullable เพื่อให้ฟิลด์หมายเหตุเป็นค่าว่างได้
    ]);

    $repairRequest = RepairRequest::findOrFail($id);
    $repairRequest->update([
        'note' => $validatedData['note'] ?? null,  // อัปเดตเป็น null ถ้าฟิลด์ว่าง
    ]);

    return redirect()->route('it') // เปลี่ยนเส้นทางให้เหมาะสม
                     ->with('success', 'Note added successfully.');
}

    // ฟังก์ชันสำหรับแสดงฟอร์มประเมิน
    public function showEvaluationForm($id)
    {
        $request = RepairRequest::findOrFail($id);
    
        // ตรวจสอบว่าสถานะเป็น "ดำเนินการแล้ว" หรือไม่
        if ($request->status->Statusname !== 'ดำเนินการแล้ว') {
            return redirect()->back()->with('error', 'สามารถประเมินได้ตอนแจ้งซ่อมเสร็จแล้วเท่านั้น.');
        }
    
        return view('repair_requests.evaluate', compact('request'));
    }
    

    // ฟังก์ชันสำหรับบันทึกการประเมิน
    public function storeEvaluation(Request $request, $id)
    {
        // Validate input
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:3', // Rating 1-3
        ]);
    
        // Find the repair request
        $repairRequest = RepairRequest::findOrFail($id);
    
        // Check if the status is "ดำเนินการแล้ว"
        if ($repairRequest->status->Statusname !== 'ดำเนินการแล้ว') {
            return redirect()->back()->with('error', 'สามารถประเมินได้ตอนแจ้งซ่อมเสร็จแล้วเท่านั้น.');
        }
    
        // Check if there's already an evaluation
        if ($repairRequest->evaluation()->exists()) {
            return redirect()->back()->with('error', 'ไม่สามารถทำการประเมินซ้ำได้.');
        }
    
        // Save the evaluation
        Evaluation::create([
            'repair_request_id' => $repairRequest->TicketID,
            'user_id' => Auth::id(),
            'rating' => $validatedData['rating'],
        ]);
    
        // Update the is_evaluated flag
        $repairRequest->is_evaluated = true;
        $repairRequest->save();
    
        // Debugging: Log the state after saving
       
    
        return redirect()->route('repair_request.index')->with('success', 'ประเมินผลเรียบร้อยแล้ว');
    }
    
    public function viewEvaluations()
{
    $user = Auth::user();

    // Check if the user is an Admin
    if ($user->role != 0) {
        return redirect()->route('dashboard')->with('error', 'Only admins can view evaluations.');
    }

    // Get all evaluations along with related repair requests
    $evaluations = Evaluation::with('repairRequest', 'user')->get();

    return view('repair_requests.evaluations', compact('evaluations'));
}


}