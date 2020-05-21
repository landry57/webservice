<?php

namespace App\Http\Controllers\Image_s;

use App\Http\Controllers\ApiController;
use App\Image_s;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Image_sController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $image = Image_s::all();
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
            'imgs' => 'required|file',
            'product_id' => 'required|integer',
        ]);

        if ($files = $request->file('imgs')) {
            $destinationPath = 'images/subimage/'; // upload path
            $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profilefile);
            $data['imgs'] = $destinationPath . "$profilefile";
        }
        try {
            $res = Image_s::create($data);

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
            $res = Image_s::find($id);
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
        $data = Image_s::findOrFail($id);

        if ($data->isDirty()) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
        if ($files = $request->file('imgs')) {
            if (Storage::exists($data['imgs'])) {
                File::delete($data['imgs']);
            }

            $destinationPath = 'images/subimage/'; // upload path

            $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profilefile);
            $data['imgs'] = $destinationPath . "$profilefile";
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
            $data = Image_s::find($id);
            if (!$data) {
                return $this->errorResponse('Image not found by ID', 400);
            }
            if (Storage::exists($data['imgs'])) {
                File::delete($data['imgs']);
            }
            $data->Delete();
            return $this->showOne($data);
        } catch (Exception $e) {
        }
    }
 
}
