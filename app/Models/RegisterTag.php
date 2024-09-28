<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterTag extends Model
{
    use HasFactory;

    protected $fillable = [  'repair_request_id',
    'TagNumber',
    'EquipmentNumber',
    'features',
    'room',
    'department',
    'building_id',
    'floor',];

    // เชื่อมความสัมพันธ์กับ RepairRequest
    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'repair_request_id');
    }
}
