<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('children')->get();
       

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
            'categorieName' => 'required|string|unique:categories',
            ]);
        $res = Category::create($data);
        return $this->showOne($res,201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
            $categories = Category::with('children')->find($id);
        
        if(!$categories)
        {
            return $this->errorResponse('Not Found!', 404);
        }
           
        return $this->showOne($categories);
    
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
        $data = Category::withTrashed()->findOrFail($id);

        if ($data->isDirty()) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
      
        if ($request->has('categorieName')) {
            $data->categorieName = $request->categorieName;
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
        $data = Category::find($id);
        if (!$data) {
            return $this->errorResponse('Category not found by ID', 400);
        }
        $data->Delete();
        return $this->showOne($data);
    } catch (Exception $e) {
    }
    }
}
