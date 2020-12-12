<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use App\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $buyers = Transaction::withTrashed()->with('product','secteur','buyer')
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
           'order_detail'=>'required',
           'lieu'=>'required'
        ]);
        //var_dump($data['order_detail']);
        $userid = Auth::user()->id;
        $idsecteur=(int)$data['lieu'][0]['id'];
        $lieu =$data['lieu'][0];
        $orderd=$data['order_detail'];
        $total_price=0;
        $res=[];
        foreach($data['order_detail'] as $order){
        $total_price += ($order["solde"] *(int)$order["quantity"]);
         $data['quantity']=(int)$order['quantity'];
         $data['product_id']=(int)$order['id'];
         $data['secteur_id']=$idsecteur;
         $data['category_id']=(int)$order['category_id'];
         $data['buyer_id']=(int)$userid;
         $data['status'] = Transaction::UNDELIVRE;
         $res[] = Transaction::create($data);
         DB::table('product_sub_category')->insert(
            ['category_id' => $data['category_id'], 'product_id' =>$data['product_id']]
        );
         };
        if(!$res){
            return $this->errorResponse('Bad request',400);
        }
         
        //userinfo
        $infouser = User::find($userid);
        $inf=['name'=> $infouser['name'],'phone'=> $infouser['phone'],'email'=> $infouser['email']];
        //send mail
          $this->userEmail =  $infouser['email'];
        
            $data =['name'=>'landry'];
            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('mail.commande', [
                'customer_details'=> $lieu,
                'user'=> $inf,
                'total_price'=>$total_price,
                'order_details'=>$orderd
        ], function($message) 
            {
               
                $email =  $this->userEmail;
                $message 
                    ->from('info@yetibeautyhair.net')
                    ->to($email)
                    ->subject('Commande');
            });

        
            $inf=['name'=> $infouser['name'],'phone'=> $infouser['phone'],'email'=> $infouser['email']];
            //send mail
            
            
               
                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('mail.admin', [
                    'customer_details'=> $lieu,
                    'user'=> $inf,
                    'total_price'=>$total_price,
                    'order_details'=>$orderd
            ], function($message) 
                {
                   
                    $emailAD = 'landry.fof@gmail.com';
                    $message 
                        ->from('info@yetibeautyhair.net')
                        ->to($emailAD)
                        ->subject('Nouvelle Commande');
                });
    
          
        return response()->json(['data' => $res],201);
       

       
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
            $buyers = Transaction::withTrashed()->with('product','secteur','buyer','image')
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
          $data = Transaction::withTrashed()->findOrFail((int)$id);
       
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
        $data = Transaction::withTrashed()->find($id);

        if(!$data){
            throw new ModelNotFoundException('Transaction not found by ID');
      
        }
        
        $data->Delete(); 
        
        
        return $this->showOne($data);
    }
}
