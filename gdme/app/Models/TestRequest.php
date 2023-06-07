<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'status',
        'response',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
