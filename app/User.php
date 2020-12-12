<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
   
    use HasApiTokens, Notifiable;
  
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';
    const ADMIN_USER = true;
    const REGULAR_USER =false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'avatar',
        'admin',
        'password',
        'status', 
        'activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];




    public  function transactions()
    {
        return $this->hasMany(Transaction::class);
    }











    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    public static function getNameAttribute($name)
    {
       return ucwords($name);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }
    public function isAdmin(){
        return $this->admin==User::ADMIN_USER;
    }
   
    


}
