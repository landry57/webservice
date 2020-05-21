<?php

namespace App\Http\Controllers\Saller;

use App\Http\Controllers\ApiController;
use App\Saller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SallerController extends ApiController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers = Saller::withTrashed()->has('products')->get();
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Saller $saller)
    {
       
        try
         {
             $buyers = Saller::withTrashed()->with('products')->get();
              return $this->showOne($saller);
         }
        catch(ModelNotFoundException $e)
     {
        return $this->errorResponse('Not Found!', 404);
  }
           
      
    }

  
}


