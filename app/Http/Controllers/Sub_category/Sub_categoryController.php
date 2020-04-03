<?php

namespace App\Http\Controllers\Sub_category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Sub_category;
use Exception;
use Illuminate\Http\Request;

class Sub_categoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Sub_category::with('parent')->get();


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
            'title' => 'required|string|unique:sub_categories',
        ]);
        $res = Sub_category::create($data);
        return $this->showOne($res, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $categories = Sub_category::with('parent')->find($id);

        if (!$categories) {
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
        $data = Sub_category::findOrFail($id);

        if ($data->isDirty()) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }

        if ($request->has('title')) {
            $data->name = $request->name;
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
            $data = Sub_category::find($id);
            if (!$data) {
                return $this->errorResponse('Category not found by ID', 400);
            }
            $data->Delete();
            return $this->showOne($data);
        } catch (Exception $e) {
        }
    }
}
