<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{


    const DELIVRE = true;
    const UNDELIVRE = false;
 
    protected $fillable= [
        'id',
        'quantity',
        'confirmed',
        'price',
        'status',
        'product_id',
        'user_id'
    ];

    public function isDelivre()
    {
        return $this->status = Transaction::DELIVRE;
       
    }

    public function order(){
        return $this->belongsTo("App\Product","product_id");
    }

    public function buyer(){
        return $this->belongsTo("App\User","user_id");
    }
   

}
