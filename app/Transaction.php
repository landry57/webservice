<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Buyer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use SoftDeletes;
    protected $dates =['deleted_at'];
    const DELIVRE = true;
    const UNDELIVRE = false;
 
    protected $fillable= [
        'quantity',
        'status',
        'product_id',
        'buyer_id',  
        'secteur_id'  
    ];

    public function isDelivre()
    {
        return $this->status = Transaction::DELIVRE;
    }

    public function buyer()
    {
     return $this->belongsTo(Buyer::class);
    }

    public function secteur()
    {
     return $this->belongsTo(Secteur::class);
    }


    
    public function product()
    {
     return $this->belongsTo(Product::class);
    }

  
    public function trasact()
    {
        return $this->hasOne(Transaction::class);
    }

   

}
