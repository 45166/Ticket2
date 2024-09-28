<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    // แสดงฟอร์มสำหรับเพิ่มข้อมูล
    public function create()
    {
        return view('buildings.create');
    }

    // บันทึกข้อมูลที่ได้รับจากฟอร์ม
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูล
        $validated = $request->validate([
            'building' => 'required|string|max:255',
            'floor' => 'required|integer',
         
        ]);

        // สร้างข้อมูลในฐานข้อมูล
        Building::create($validated);

        // Redirect ไปหน้าที่ต้องการ (เช่น หน้ารายการตึก)
        return redirect()->route('buildings.create')->with('success', 'เพิ่มอาคารสำเร็จ!');
    }
}
