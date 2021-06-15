<?php

namespace App\Models;

class ArtworkBackup extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'backup',
        'nome',
        'autore',
        'anno_inizio_creazione',
        'anno_ultimatura',
        'immagine_opera'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'backup',
        'created_at',
        'updated_at'
    ];

    public function backup(){
        return $this->belongsTo("App\Models\Backup", "backup");
    }
}

?>