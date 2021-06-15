<?php

namespace App\Models;

class PrivateMuseum extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome_societa',
    ];

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
