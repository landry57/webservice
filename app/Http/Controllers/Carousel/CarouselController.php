<?php

namespace App\Http\Controllers\Carousel;

use App\Carousel;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CarouselController extends ApiController
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $image = Carousel::all();
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
            'image' => 'required|file',
            'title' => 'required|string|unique:carousels',
            'sub_title' => 'required|string'
        ]);
          $data['is_active'] =Carousel::STATUS;

        if ($files = $request->file('image')) {
            $destinationPath = 'images/carousel/'; // upload path
            $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profilefile);
            $data['image'] = $destinationPath . "$profilefile";
        }
        try {
            $res = Carousel::create($data);

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
            $res = Carousel::find($id);
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
        $data = Carousel::find($id);

        if (is_null($data)) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
        if ($files = $request->file('image')) {
            if (Storage::exists($data['image'])) {
                File::delete($data['image']);
            }

            $destinationPath = 'images/carousel/'; // upload path

            $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profilefile);
            $data['image'] = $destinationPath . "$profilefile";
        }

        if ($request->has('title')) {
            $data->title = $request->title;
        }
        if ($request->has('sub_title')) {
            $data->sub_title = $request->sub_title;
        }
        if ($request->has('is_active')) {
            $data->is_active = (int)$request->is_active;
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
            $data = Carousel::find($id);
            if (!$data) {
                return $this->errorResponse('Image not found by ID', 400);
            }
            if (Storage::exists($data['image'])) {
                File::delete($data['image']);
            }
            $data->Delete();
            return $this->showOne($data);
        } catch (Exception $e) {
        }
    }
}
