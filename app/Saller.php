<?php

namespace App;
use App\Product;
use App\Scopes\SallerScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saller extends User
{

    use SoftDeletes;
    protected $dates =['deleted_at'];
     protected static function boot()
     {
         parent::boot();
         static::addGlobalScope(new SallerScope);
     }
    public function products()
   {
        return $this->hasMany(Product::class);
   } 
}
