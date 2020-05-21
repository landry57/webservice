<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sub_category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image_s extends Model
{
   // use SoftDeletes;
    protected $dates =['deleted_at'];
    protected $fillable= [
        'imgs',
        'product_id'
    ];

    public function sub_category()
    {
        return $this->hasOne(Image_p::class);
    }

    

    public function scateg()
    {
        return $this->hasOne(Sub_category::class,'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
