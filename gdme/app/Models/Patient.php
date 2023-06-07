<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
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
        'person_contact',
        'person_contact_phone',
        'person_contact_wilaya',
        'height',
        'weight',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
