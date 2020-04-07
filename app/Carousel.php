<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carousel extends Model
{
    //use SoftDeletes;
    protected $dates =['deleted_at'];
    const STATUS= 1;
    const UNSTATUS= 0;
    protected $fillable= [
        'title',
        'sub_title',
        'image',
        'is_active',
       
    ];

    public function isActive()
    {
        return $this->status = Carousel::STATUS;
    }
}
