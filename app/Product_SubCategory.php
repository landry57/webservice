<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_SubCategory extends Model
{  
    use SoftDeletes;
    protected $dates =['deleted_at'];
    protected $fillable= [
        'sub_category_id',
        'product_id'
    ];

}

