<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Buyer;
use App\Http\Controllers\ApiController;
use Dotenv\Parser;
use Exception;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers = Buyer::withTrashed()->has('transactions')->get();
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
            $buyers = Buyer::withTrashed()->has('transactions')->find($id);
        }
        catch(ModelNotFoundException $e)
        {
            return $this->errorResponse('Not Found!', 404);
        }
           
        return $this->showOne($buyers);
    }

  
}
