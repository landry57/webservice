<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers = User::has('transactions')->get();
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        try
        {
            $buyers = User::has('transactions')->find($id);
        }
        catch(ModelNotFoundException $e)
        {
            return $this->errorResponse('Not Found!', 404);
        }
           
        return $this->showOne($buyers);
    }

  
}
