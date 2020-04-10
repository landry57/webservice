<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
      
      public function sendOrderEmail()
      {
          $title = '[Confirmation] Thank you for your order';
          $customer_details = [
              'name' => 'Arogya',
              'address' => 'kathmandu Nepal',
              'phone' => '123123123',
              'email' => 'landry.fof@gmail.com'
          ];
          $order_details = [
              'SKU' => 'D-123456',
              'price' => '10000',
              'order_date' => '2020-01-22',
          ];
  
          $sendmail = Mail::to($customer_details['email'])->send(new SendMail($title, $customer_details, $order_details));
          if (empty($sendmail)) {
              return response()->json(['message' => 'Mail Sent Sucssfully'], 200);
          }else{
              return response()->json(['message' => 'Mail Sent fail'], 400);
          }
      }


    public function sendEMail(){
		// $this->validate($request, [
	    //     'email' => 'bail|required|email',
        // ]);
        $customer_details = [
            'name' => 'Fofana landry',
            'phone' => '+22557534697',
            'email' => 'landry.fof@gmail.com'
        ];
        $order_details = [
            
            'pname' => 'zum',
            'price' => '10000',
            'quanty'=>'1',
            
        ];
        $data =['name'=>'landry'];
		$beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('mail.commande', [
            'customer_details'=>$customer_details,
            'order_details'=>$order_details
    ], function($message) 
	    {
           
	    	$email = 'landry.fof@gmail.com';
	        $message 
				->from('info@yetibeautyhair.net')
				->to($email)
				->subject('Commande');
	    });
	   return response()->json(['message'=>'success']);
	}  
}
