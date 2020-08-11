<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable= [
        'img',
        'id_product_fk',
        'status'
    ];
}
