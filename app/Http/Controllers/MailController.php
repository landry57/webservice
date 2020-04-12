<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
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


    public function sendEMail(Request $request){
		$this->validate($request, [
	        'tab' => 'required',
        ]);
        $this->emailfrom=$request['tab']['email'];
        $tab = [
            'name' => $request['tab']['name'],
            'email' => $request['tab']['email'],
            'subject'=>$request['tab']['subject'],
            'message'=>$request['tab']['message']  
        ];
       
		$beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('contact', [
            'tab'=>$tab
           
    ], function($message) 
        {   
            $email = 'landry.fof@gmail.com';
	        $message 
				->from($this->emailfrom)
				->to($email)
				->subject('Contact');
        });
        
        if (empty($sendmail)) {
            return response()->json(['message' => 'Mail Sent Sucssfully'], 200);
        }else{
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
	}  
}
