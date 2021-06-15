<?php

namespace App\Models;

class Province extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sigla',
        'provincia',
        'superficie',
        'residenti',
        'num_comuni',
        'id_regione'
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

    public function cities(){
        return $this->hasMany("App\Models\City", "provincia");
    }
}

?>
