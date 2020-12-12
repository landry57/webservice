<?php

namespace App\Http\Controllers\Buyer;

use App\User;
use App\Http\Controllers\ApiController;


class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
      $products =$user->transactions()->with('products','pictures')
      ->get();
      //->pluck('product');
      return $this->showAll($products);
    }

   
}
