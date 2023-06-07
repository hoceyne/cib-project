<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'firstname',
        'lastname',
        'role',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function attendences(){
        return $this->hasMany(Attendence::class);
    }

}
