<?php

namespace App\Http\Controllers\Secteur;

use App\Http\Controllers\ApiController;
use App\Secteur;
use Exception;
use Illuminate\Http\Request;

class SecteurController extends ApiController
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $secteurs = Secteur::all();
       

        if (!$secteurs) {
            return $this->respondNotFound('secteur does not exists');
        }
        return $this->showAll($secteurs);
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
            'name' => 'required|string|unique:secteurs',
            'montant' => 'required|integer',
            ]);
        $res = Secteur::create($data);
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
        
            $categories = Secteur::findOrFail($id);
        
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
        $data = Secteur::findOrFail($id);

        if ($data->isDirty()) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
      
        if ($request->has('name')) {
            $data->name = $request->name;
        }
        if ($request->has('montant')) {
            $data->montant = $request->montant;
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
        $data = Secteur::find($id);
        if (!$data) {
            return $this->errorResponse('secteur not found by ID', 400);
        }
        $data->Delete();
        return $this->showOne($data);
    } catch (Exception $e) {
    }
    }
}
