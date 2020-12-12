<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{

    const STATUS= 1;
    const UNSTATUS= 0;
    protected $fillable= [
        'title',
        'sub_title',
        'image',
        'status',
       
    ];

    public function isActive()
    {
        return $this->status = Carousel::STATUS;
    }
}
