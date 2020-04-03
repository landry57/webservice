<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sub_category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image_p extends Model
{
    use SoftDeletes;
    protected $dates =['deleted_at'];
    protected $fillable= [
        'img',
        'product_id'
    ];

    public function sub_category()
    {
        return $this->hasOne(Sub_category::class);
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
