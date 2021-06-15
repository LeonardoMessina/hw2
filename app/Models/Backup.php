<?php

namespace App\Models;

class Backup extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'museo',
        'data'
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
        return $this->belongsTo("App\Models\Museum");
    }

    public function artwork_backups(){
        return $this->hasMany("App\Models\ArtworkBackup", "museo");
    }
}

?>
