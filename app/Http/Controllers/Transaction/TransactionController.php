<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Product_SubCategory;
use App\Transaction;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $buyers = Transaction::with('product','secteur','buyer','image')
            ->get()
            ;
           
            return $this->showAll($buyers);
        }
        catch(ModelNotFoundException $e)
        {
            return $this->errorResponse('Not Found!', 404);
        }
           
    

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->validate([
            'quantity' => 'required|integer',
            'buyer_id' => 'required|integer',
            'product_id' => 'required|integer',
            'buyer_id' => 'required|integer',
            'secteur_id' => 'required|integer',
            'sub_category_id'=>'required|integer'
        ]);
        $data['status'] = Transaction::UNDELIVRE;
       
        try {
            $res = Transaction::create($data);
            DB::table('product_sub_category')->insert(
                ['sub_category_id' => $request->sub_category_id, 'product_id' => $request->product_id]
            );
          
        return response()->json(['data' => $res],201);
        }catch(Exception $e){
            echo $e;
            return $this->errorResponse('Bad request',400);
        }
      

       
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
            $buyers = Transaction::with('product','secteur','buyer','image')
            ->get()
            ->where('buyer.id','=',$id);
           
            return $this->showAll($buyers);
        }
        catch(ModelNotFoundException $e)
        {
            return $this->errorResponse('Not Found!', 404);
        }
           
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $data = Transaction::findOrFail((int)$id);
       
           if ($request->has('status')) {
            $data->status = (int)$request->status;
           
            }
    
            if ($request->has('quantity')) {
            $data->quantity = (int)$request->quantity;
            }

            if ($request->has('secteur_id')) {
                $data->secteur_id = (int)$request->secteur_id;
                }
        if (!$data) {
          return  $this->errorResponse('You need to specify a different value to update', 422);
        }
        $data->save();
        return $this->showOne($data);
    }

  
  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Transaction::find($id);

        if(!$data){
            throw new ModelNotFoundException('Transaction not found by ID');
      
        }
        
        $data->Delete(); 
        
        
        return $this->showOne($data);
    }
}
