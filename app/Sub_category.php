<?php

namespace App;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sub_category extends Model
{

    use SoftDeletes;
    protected $dates =['deleted_at']; 
    protected $fillable= [
        'title',
        'category_id'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

        public function parent()
    {
        return $this->belongsTo('Sub_category', 'category_id');
    }

   
}
