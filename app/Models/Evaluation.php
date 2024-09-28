<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations';  // ชื่อตารางที่ตรงกับในฐานข้อมูล

    protected $fillable = [            // ฟิลด์ที่สามารถกรอกได้
        'repair_request_id', 'user_id', 'rating'
    ];

    // ความสัมพันธ์กับ RepairRequest
    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'repair_request_id', 'TicketID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
