<?php

namespace App\Models;

class PublicMuseum extends BaseModel
{

    protected $primaryKey='museo';
    protected $autoIncrement=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function museum(){
        return $this->belongsTo("App\Models\Museum", "museo");
    }
}

?>
