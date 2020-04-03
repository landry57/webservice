<?php

namespace App;
use App\Sub_category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{  
    use SoftDeletes;
    protected $dates =['deleted_at'];
    protected $fillable= [
        'id',
        'name',
    ];



 public function children()
  {
    return $this->hasMany(Sub_category::class, 'category_id');
  }
}

