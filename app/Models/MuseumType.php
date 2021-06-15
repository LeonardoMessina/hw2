<?php

namespace App\Models;

class MuseumType extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function museums(){
        return $this->hasMany("App\Models\Museum", "tipo");
    }
}

?>
