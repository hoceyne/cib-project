<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'medication',
        'dosage',
        'quantity',
        'dosage_form',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
}
