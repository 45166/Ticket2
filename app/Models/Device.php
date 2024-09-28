<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';

    // Remove DeviceID from fillable since it's auto-incrementing
    protected $fillable = [
        'Devicename', 'DeviceType'
    ];

    // Define the primary key if it's not the default 'id'
    protected $primaryKey = 'DeviceID';

    // Indicate that DeviceID is an auto-incrementing field
    public $incrementing = true;

    // Use integer for primary key type if necessary
    protected $keyType = 'int';

    // Define the relationship with the RepairRequest model
    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class, 'Device_ID', 'DeviceID');
    }
}
