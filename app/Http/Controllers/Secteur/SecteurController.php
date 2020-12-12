<?php

namespace App\Http\Controllers\Secteur;

use App\Secteur;
use App\Http\Controllers\ApiController;
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
        $secteurs = Secteur::get();
       

        if (!$secteurs) {
            return $this->respondNotFound('secteurs does not exists');
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
            'secteurName' => 'required|string|unique:secteurs',
            ]);
    
         $data['status']=1;   
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
        
            $secteurs = Secteur::find($id);
        
        if(!$secteurs)
        {
            return $this->errorResponse('Not Found!', 404);
        }
           
        return $this->showOne($secteurs);
    
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
        $data = Secteur::find($id);

        if (is_null($data)) {
            return  $this->errorResponse('Bad request', 400);
        }
        if (!$request) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }
      
        if ($request->has('secteurName')) {
            $data->secteurName = $request->secteurName;
        }
        if ($request->has('status')) {
            $data->status = (int)$request->status;
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
            return $this->errorResponse('Secteur not found by ID', 400);
        }
        $data->Delete();
        return $this->showOne($data);
    } catch (Exception $e) {
    }
    }
}
