<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Buyer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use SoftDeletes;
    protected $dates =['deleted_at'];
    const DELIVRE = true;
    const UNDELIVRE = false;
 
    protected $fillable= [
        'id',
        'numero_commande',
        'quantity',
        'status',
        'id_product_fk',
        'id_buyer_fk'
    ];

    public function isDelivre()
    {
        return $this->status = Transaction::DELIVRE;
    }

    
   

}
