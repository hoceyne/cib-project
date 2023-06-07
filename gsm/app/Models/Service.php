<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'medical_staff',
        'paramedical_staff',
        'bed_capacity',
        'email',
        'phone_number',
        'fax',

    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function cheif_doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function activities()
    {
        return $this->hasMany(ServiceActivity::class);
    }
    public function outlooks()
    {
        return $this->hasMany(ServiceOutlook::class);
    }
    public function main_photo()
    {
        return $this->hasOne(ServiceMainPhoto::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function departement(){
        return $this->belongsTo(Departement::class);
    }
}
