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
        'gender',
        'date_of_birth',
        'specialization',
        'phone_number',

    ];

    protected $keyType = 'string';
    public $incrementing = false;
}
