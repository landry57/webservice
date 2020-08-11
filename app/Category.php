<?php

namespace App;
use App\Sub_category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{  
  
    protected $fillable= [
        'id',
        'name',
    ];




}

