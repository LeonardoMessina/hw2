<?php

namespace App\Models;

class City extends BaseModel
{

    protected $table='cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'istat',
        'comune',
        'regione',
        'provincia',
        'prefisso',
        'cod_fisco',
        'superficie',
        'num_residenti'
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

    public function province(){
        return $this->belongsTo("App\Models\Province", "provincia");
    }

    public function museums(){
        return $this->hasMany("App\Models\Museum", "citta");
    }
}

?>
