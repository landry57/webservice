<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secteur extends Model
{  
    use SoftDeletes;
    protected $fillable= [
        'name',
        'montant'   
    ];

    public function transaction()
    {
         return $this->hasMany(Transaction::class);
    } 
}
