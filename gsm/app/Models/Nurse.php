<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nurse extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'firstname',
        'lastname',
        'natinal_id',
        'natonality',
        'blood_group',
        'phone_number',
        'gender',
        'birth_date',
        'birth_place',
        'family_situation',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }
}
