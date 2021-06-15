<?php

namespace App\Models;

class Museum extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'citta',
        'lat',
        'lon',
        'tipo',
        'costo_biglietto',
        'data_apertura',
        'introduzione'
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

    public function city(){
        return $this->belongsTo("App\Models\City", "citta");
    }

    public function museum_type(){
        return $this->belongsTo("App\Models\MuseumType", "tipo");
    }

    public function public_museum(){
        return $this->hasOne("App\Models\PublicMuseum", "museo");
    }

    public function private_museum(){
        return $this->hasOne("App\Models\PrivateMuseum", "museo");
    }

    public function artworks(){
        return $this->hasMany("App\Models\Artwork", "museo");
    }

    public function backups(){
        return $this->hasMany("App\Models\Backup", "museo");
    }

    public function user(){
        return $this->hasOne("App\Models\User", "museo");
    }
}

?>
