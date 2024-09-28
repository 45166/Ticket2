<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tags';
    protected $primaryKey = 'TagID';
    public $incrementing = false;
    protected $keyType = 'char';

    protected $fillable = [
        'TagID',
        'TagNumber',
    ];
}
