<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendence extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'date',
        'presence',
        'punch_in',
        'punch_out',
        

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
