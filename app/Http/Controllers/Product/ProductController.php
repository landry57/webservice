<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Image_p;
use App\Image_s;
use App\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        //$categories = Product::with('imageprincipale','children')->get();
        $categories = Product::withTrashed()->with('imageprincipale','children')->get();
       
       
        if (!$categories) {
            return $this->respondNotFound('Categories does not exists');
        }
        return $this->showAll($categories);
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
            'name' => 'required|string|unique:products',
            'code' => 'required|string|unique:products',
            'description' => 'required|string',
            'price' => 'required|integer',
            'saller_id' => 'required|integer',
            'sub_category_id' => 'required|integer'
        ]);

        if($request->solde && !empty($request->solde)){
            $data['solde'] =(int)$request->solde;
        }

        $data['status']=Product::AVAILABLE_PRODUCT; 

     
        try {
            $res = Product::create($data);
            return response()->json(['data' => $res], 201);
        } catch (Exception $e) {
            return $this->errorResponse('Bad request', 400);
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
        try {
            //$res = Product::where('id','=',$id)->with('imageprincipale','children')->get();
            $res = Product::where('id',$id)->withTrashed()->with('imageprincipale','children')->get();
            if (!$res) {
                return $this->errorResponse('Product not found by ID', 400);
            }
            return response()->json(['data'=>$res],200);
        } catch (Exception $e) {
            return $this->errorResponse('Product not found by ID', 400);
        }  //
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
        try {
        //$data = Product::findOrFail($id);
        $data=Product::withTrashed()->find($id);
        if ($data->isDirty()) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
      
        if ($request->has('name')) {
            $data->name = $request->name;
        }
        if ($request->has('code')) {
            $data->code = (int)$request->code;
        }

        if ($request->has('description')) {
            $data->description = $request->description;
        }
        if ($request->has('status')) {
            $data->status = (int)$request->status;
        }
        if ($request->has('price')) {
            $data->price = (int)$request->price;
        }
        if ($request->has('solde')) {
            $data->solde = (int)$request->solde;
        }

        if ($request->has('seller_id')) {
            $data->seller_id = (int)$request->seller_id;
        }
        if ($request->has('deleted_at')) {
            $data->deleted_at =null;
        }
       
           $data->save();
           return $this->showOne($data);
       }catch(Exception $e){
        return  $this->errorResponse('Bad request', 400);
       }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Product::find($id);
            
            if (!$data) {
                return $this->errorResponse('Product not found by ID', 400);
            }
            
            $data->Delete();
           
            return $this->showOne($data);
        } catch (Exception $e) {
        }
    }
}
