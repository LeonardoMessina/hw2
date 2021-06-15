<?php

namespace App\Models;

class User extends BaseModel{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'museo',
        'username',
        'email',
        'telefono1',
        'telefono2',
        'data_registrazione'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'museo',
        'password',
        'created_at',
        'updated_at'
    ];

    public function museum(){
        return $this->belongsTo("App\Models\Museum", "museo");
    }
}

?>
