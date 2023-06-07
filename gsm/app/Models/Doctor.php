<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'firstname',
        'lastname',
        'specialization',
        'birth_date',
        'birth_place',
        'phone_number',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function service(){
        return $this->belongsTo(Service::class); 
    }
    public function service_responsible(){
        return $this->hasOne(Service::class); 
    }
}
