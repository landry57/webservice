<?php

namespace App;
use App\Saller;
use App\Sub_category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    const AVAILABLE_PRODUCT = true;
    const UNAVAILABLE_PRODUCT = false;
    protected $dates =['deleted_at'];
    protected $fillable= [
        'name',
        'description',
        'quantity',
        'status',
        'price',
        'solde',
        'sub_category_id',
        'saller_id'
    ];

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function seller()
    {
        return $this->belongsTo(Saller::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function sub_categories()
    {
        return $this->belongsToMany(Sub_category::class);
    }

    public function imageprincipale()
  {
    return $this->hasMany(Image_p::class, 'product_id');
  }

  public function children()
  {
    return $this->hasMany(Image_s::class, 'product_id');
  }
 
}
