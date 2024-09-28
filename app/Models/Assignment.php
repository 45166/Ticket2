<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';

    protected $fillable = [
        'TicketID', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'TicketID', 'TicketID');
    }
}
