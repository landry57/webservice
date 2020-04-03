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
    ];

    public function isDelivre()
    {
        return $this->status = Transaction::DELIVRE;
    }

    public function buyer()
    {
     return $this->belongsTo(Buyer::class);
    }

    
    public function product()
    {
     return $this->belongsTo(Product::class);
    }

      public function image()
    {
        return $this->hasOne(Image_p::class,'product_id');
    }

    public function trasact()
    {
        return $this->hasOne(Transaction::class);
    }

   

}
