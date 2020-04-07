<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    protected $fillable= [
        'name',
        'montant'   
    ];

    public function transaction()
    {
         return $this->hasMany(Transaction::class);
    } 
}
