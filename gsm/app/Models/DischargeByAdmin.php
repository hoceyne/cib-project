<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DischargeByAdmin extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $fillable = [
        'date',
        'service_total_cost',
        'discharge_document_type',
        'accompanied_on_leaving_by'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
    public function reciept(){
        return $this->belongsTo(Reciept::class);
    }
    public function discharge_file(){
        return $this->belongsTo(Discharge::class);
    }
}
