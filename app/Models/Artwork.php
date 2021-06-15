<?php

namespace App\Models;

class Artwork extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'autore',
        'anno_inizio_creazione',
        'anno_ultimatura',
        'museo',
        'immagine_opera'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'museo',
        'created_at',
        'updated_at'
    ];

    public function museum(){
        return $this->belongsTo("App\Models\Museum", "museo");
    }
}

?>
