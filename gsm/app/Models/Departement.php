<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departement extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'name',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

}
