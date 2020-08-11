<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{


    const DELIVRE = true;
    const UNDELIVRE = false;
 
    protected $fillable= [
        'id',
        'quantity',
        'status',
        'id_product_fk',
        'id_buyer_fk'
    ];

    public function isDelivre()
    {
        return $this->status = Transaction::DELIVRE;
    }

    
   

}
