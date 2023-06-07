<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,HasUuids,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'national_id',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'id'=>'string',
    ];

    public function  employee(){
        return $this->hasOne(Employee::class);
    }

    public function  patient(){
        return $this->hasOne(Patient::class);
    }

    public function  address(){
        return $this->hasOne(Address::class);
    }

    public function profile_picture(){
        return $this->hasOne(File::class);
    }

    public function responds()
    {
        # code...
        return $this->hasMany(ContactRespond::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }
}
