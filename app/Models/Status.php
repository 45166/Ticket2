<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses'; // ใช้ชื่อตารางที่ถูกต้อง

    protected $fillable = [
        'StatusID', 'Description'
    ];

    // Define the relationship with the RepairRequest model
    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class, 'StatusID', 'StatusID');
    }
}
