<?php

namespace App\Http\Controllers\Picture;

use App\Http\Controllers\ApiController;
use App\Picture;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PictureController extends ApiController
{ /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $image = Picture::with('product', 'scateg')->get();
       return $this->showAll($image);
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
           'img' => 'required|image|mimes:jpeg,png,jpg,gif',
           'id_product_fk' => 'required|integer',
       ]);

      
      
       if ($request->has('img')) {
          
           $files = $request->file('img');
           $destinationPath = 'images/product/'; // upload path
           $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profilefile);
           $data['img'] = $destinationPath . "$profilefile";
       }
       try {
           $res = Picture::create($data);

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
           $res = Picture::find($id);
           if (!$res) {
               return $this->errorResponse('Image not found by ID', 400);
           }
           return $this->showOne($res);
       } catch (Exception $e) {
          
           return $this->errorResponse('Image not found by ID', 400);
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
       $data = Picture::findOrFail($id);

       if ($data->isDirty()) {
           return  $this->errorResponse('Bad request', 400);
       }
       if (!$request) {
           return  $this->errorResponse('You need to specify a different value to update', 422);
       }
       if ($files = $request->file('img')) {
           if (Storage::exists($data['img'])) {
               File::delete($data['img']);
           }

           $destinationPath = 'images/product/'; // upload path

           $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profilefile);
           $data['img'] = $destinationPath . "$profilefile";
       }

       if ($request->has('product_id')) {
           $data->product_id = $request->product_id;
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
       try {
           $data = Picture::find($id);
           if (!$data) {
               return $this->errorResponse('Image not found by ID', 400);
           }
           if (Storage::exists($data['img'])) {
               File::delete($data['img']);
           }
           $data->Delete();
           return $this->showOne($data);
       } catch (Exception $e) {
       }
   }
}
