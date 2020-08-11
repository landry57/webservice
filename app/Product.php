<?php

namespace App;
use App\Saller;
use App\Sub_category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    
    const AVAILABLE_PRODUCT = true;
    const UNAVAILABLE_PRODUCT = false;
    protected $fillable= [
        'name',
        'description',
        'code',
        'status',
        'price',
        'solde',
        'id_category_fk'
    ];

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

 
}
