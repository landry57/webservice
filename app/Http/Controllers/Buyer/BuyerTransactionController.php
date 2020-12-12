<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $buyer)
    {
       $transactions = $buyer->transactions;
       return $this->showAll($transactions);
    }

   
}
