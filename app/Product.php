<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;
class Product extends Model
{
    
    const AVAILABLE_PRODUCT = true;
    const UNAVAILABLE_PRODUCT = false;
    protected $fillable= [
        'productName',
        'description',
        'reference',
        'status',
        'regular_price',
        'discount_price',
        'categorie_id'
    ];

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

     public function category() {
        return $this->belongsTo('App\Category','categorie_id');
      }

      public function  pictures() {
        return $this->hasMany('App\Picture','id');
      }

      public function transactions()
      {
          return $this->hasMany(Transaction::class);
      }
  
 
}
