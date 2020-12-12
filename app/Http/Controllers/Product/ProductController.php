<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Image_p;
use App\Image_s;
use App\Product;
use App\Picture;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
       
        $categories = Product::with('category','pictures')->get();
       
       
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
            'productName' => 'required|string|unique:products',
            'reference' => 'required|string|unique:products',
            'description' => 'required|string',
            'regular_price' => 'required|integer',
            'categorie_id' => 'required|integer'
        ]);

        if($request->discount_price && !empty($request->discount_price)){
            $data['discount_price'] =(int)$request->discount_price;
        }

        $data['status']=Product::AVAILABLE_PRODUCT; 

     
        try {
            $res = Product::create($data);
            return response()->json(['data' => $res], 201);
        } catch (Exception $e) {
            return $this->errorResponse('Bad request '.$e, 400);
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
            $res = Product::with('categoriy','pictures')->find($id);
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
      
        $data=Product::find($id);
        if (is_null($data)) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
      
        if ($request->has('productName')) {
            $data->productName = $request->productName;
        }
        if ($request->has('reference')) {
            $data->reference = (int)$request->reference;
        }

        if ($request->has('description')) {
            $data->description = $request->description;
        }
        
        if ($request->has('status')) {
            $data->status = (int)$request->status;
        }

        if ($request->has('regular_price')) {
            $data->regular_price = (int)$request->regular_price;
        }

        if ($request->has('discount_price')) {
            $data->discount_price = (int)$request->discount_price;
        }
        if ($request->has('categorie_id')) {
            $data->categorie_id = (int)$request->categorie_id;
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
            $pictures = Picture::where('product_id', $id);
            
            if (!$data) {
                return $this->errorResponse('Product not found by ID', 400);
            }
            if ($pictures){
                foreach ($pictures as $p){
                    if (File::exists(public_path($p['path']))) {
                        File::delete(public_path($p['path']));
                    }
                }
            }
            $pictures->Delete();
            $data->Delete();
           
            return $this->showOne($data);
        } catch (Exception $e) {
        }
    }
}
