<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairRequestController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Exports\EvaluationsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\RoleController;
// หน้าแรก
Route::get('/', function () {
    return view('auth.login');
});
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');



Route::post('/role/update', [RoleController::class, 'updateRole'])->name('role.update');


// เส้นทางสำหรับ RepairRequests
Route::prefix('repair_requests')->group(function () {
    // หน้าหลักของ RepairRequests
    Route::get('/', [RepairRequestController::class, 'index'])->name('repair_requests.index');
    Route::get('/create', [RepairRequestController::class, 'create'])->name('repair_requests.create');
    Route::post('/', [RepairRequestController::class, 'store'])->name('repair_requests.store');
    Route::get('/repair-requests', [RepairRequestController::class, 'index'])->name('repair_request.index');

    
    // แสดงและจัดการโน้ต
    Route::get('{id}/note/edit', [RepairRequestController::class, 'showNoteForm'])->name('repair_requests.showNoteForm');
    Route::get('{id}/note', [RepairRequestController::class, 'showNote'])->name('repair_requests.showNote');
    Route::post('{id}/note', [RepairRequestController::class, 'storeNote'])->name('repair_requests.storeNote');
    
    // การประเมินงาน
    Route::get('{id}/evaluate', [RepairRequestController::class, 'showEvaluationForm'])->name('repair_requests.evaluate');
    Route::post('{id}/evaluate', [RepairRequestController::class, 'storeEvaluation'])->name('repair_requests.evaluate.store');
    
    // การเปลี่ยนสถานะ
    Route::get('{id}/status', [RepairRequestController::class, 'showStatusChangeForm'])->name('repair_requests.showStatusChangeForm');
    Route::post('{id}/status', [RepairRequestController::class, 'updateStatus'])->name('repair_requests.updateStatus');
    // เส้นทาง GET สำหรับแสดงฟอร์มจัดการ Role
    Route::middleware('rolemanager:admin')->group(function () {  
Route::get('/role/form', [RoleController::class, 'showForm'])->name('role.form');
Route::post('/role/update', [RoleController::class, 'updateRole'])->name('role.update');
});
    
    // การมอบหมายงาน (Admin เท่านั้น)
    Route::middleware('rolemanager:admin')->group(function () {
        Route::get('{id}/assign', [RepairRequestController::class, 'assign'])->name('repair_requests.assign');
        Route::post('{id}/assign', [RepairRequestController::class, 'storeAssignment'])->name('repair_requests.storeAssignment');
    });
Route::get('/repair-requests/filter', [RepairRequestController::class, 'filterByStatus'])->name('repair_requests.filterByStatus');



    // ดูการประเมินทั้งหมด (Admin เท่านั้น)
    Route::get('/evaluations', [RepairRequestController::class, 'viewEvaluations'])->name('repair_requests.evaluations');
    

    Route::get('/buildings/create', [BuildingController::class, 'create'])->name('buildings.create'); // แสดงฟอร์ม
    Route::post('/buildings', [BuildingController::class, 'store'])->name('buildings.store'); // บันทึกข้อมูล
    
    // เส้นทางสำหรับลงทะเบียนและค้นหา Tag
// GET request to show the tag registration form
// ใน routes/web.php

Route::get('/repair_requests/{id}/register_tag', [RepairRequestController::class, 'showRegisterTagForm'])->name('repair_requests.showTagRegisterForm');
Route::post('/repair_requests/{id}/store_tag', [RepairRequestController::class, 'storeTag'])->name('repair_requests.storeTag');

    Route::get('searchByTag', [RepairRequestController::class, 'searchByTag'])->name('repair_requests.searchByTag');
    Route::get('repair_requests/search', [RepairRequestController::class, 'showSearchForm'])->name('repair_requests.search');

});
Route::get('/report', [RepairRequestController::class, 'report'])->name('report');

Route::get('/export-evaluations', function () {
    return Excel::download(new EvaluationsExport, 'evaluations.xlsx');
})->name('evaluations.export');
Route::middleware(['rolemanager:admin'])->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::get('/devices/{id}/edit', [DeviceController::class, 'edit']);
Route::put('/devices/{id}', [DeviceController::class, 'update']);

    Route::put('/devices/{id}', [DeviceController::class, 'update'])->name('devices.update');
    Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::resource('devices', DeviceController::class);

    
});

// เส้นทางสำหรับ Event (ScheduleController)


// FullCalendar Routes
Route::get('/fullcalender', [ScheduleController::class, 'index'])->name('fullcalender.index'); // View the calendar page
Route::get('/events', [ScheduleController::class, 'getEvents'])->name('events.get'); // Fetch all events
Route::get('/events/search', [ScheduleController::class, 'search'])->name('events.search'); // Search events
Route::post('/schedule/{id}', [ScheduleController::class, 'update'])->name('events.update'); // Update event (drag and drop)
Route::post('/schedule/{id}/resize', [ScheduleController::class, 'resize'])->name('events.resize'); // Resize event
Route::get('/schedule/delete/{id}', [ScheduleController::class, 'deleteEvent'])->name('events.delete'); // Delete event
Route::get('/add-schedule', function () {
    return view('schedule.add');
})->name('schedule.add'); // Show form to add a new schedule
Route::post('/create-schedule', [ScheduleController::class, 'create'])->name('schedule.create'); // Handle creating new schedule

// ฟอร์มเพิ่ม schedule ไม่ใช้ ticketID
Route::get('/schedule/add/{ticketID}', [ScheduleController::class, 'showAddScheduleForm'])->name('add-schedule');
Route::delete('/schedule/{id}', [ScheduleController::class, 'deleteEvent'])->name('events.delete'); // Delete event




// เส้นทางสำหรับ Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    // เส้นทางสำหรับ Admin Dashboard
    Route::middleware('rolemanager:admin')->group(function () {
        Route::get('/admin/dashboard', [RepairRequestController::class, 'adminDashboard'])->name('admin');
    });

    // เส้นทางสำหรับ IT Dashboard
    Route::middleware('rolemanager:it')->group(function () {
        Route::get('/it/dashboard', [RepairRequestController::class, 'itIndex'])->name('it');
    });

    // เส้นทางสำหรับ User Dashboard
    Route::middleware('rolemanager:user')->group(function () {
        Route::get('/dashboard', [RepairRequestController::class, 'dashboard'])->name('dashboard');
    });
});

// เส้นทางสำหรับ Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// เส้นทางสำหรับ Register


require __DIR__.'/auth.php';
