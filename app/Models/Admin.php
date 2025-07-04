<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Admin extends Authenticatable
{
    use  Notifiable;

    protected $primaryKey = 'admin_id';


    /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'admin_id',
    ];


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
        'password' => 'hashed',
    ];


    public  function isAllowed($allowedRoles){

        $role = $this->role;

        $allRoles = [
            "SUPER_ADMIN" => 1,
            "ADMIN" => 2,
        ];
        

        $roles = array_map(function($roleName) use ($allRoles) {
            return $allRoles[$roleName];
        }, $allowedRoles);

        $role = explode(',', $role);
        if (array_intersect($role, $roles)) {
            return true;
        }

        return false;


    }

    public static function adminRoles(){

        return  [
            "SUPER ADMIN" => 1,
            "ADMIN" => 2,
        ];
    }

    
    public function smsState(): HasOne
    {
        return $this->hasOne(SmsState::class, 'phone');
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


   
}
