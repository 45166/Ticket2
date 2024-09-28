<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    // ฟอร์มสำหรับเปลี่ยน Role
    public function showForm()
    {
        return view('role_form');  // อ้างอิงถึงวิวที่จะแสดงฟอร์ม
    }

    // เมธอดสำหรับอัปเดต Role
    public function updateRole(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับ
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|integer',
        ]);

        // ค้นหาผู้ใช้ตามอีเมล
        $user = User::where('email', $request->email)->first();

        // อัปเดต Role
        $user->role = $request->role;
        $user->save();

        // ส่งกลับไปยังหน้าเดิมพร้อมข้อความสำเร็จ
        return redirect()->back()->with('success', 'Role updated successfully!');
    }
}
