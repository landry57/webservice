<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
      //
    //   public function sendEmail()
    //   {
    //       $title = '[Confirmation] Thank you for your order';
    //       $customer_details = [
    //           'name' => 'Arogya',
    //           'address' => 'kathmandu Nepal',
    //           'phone' => '123123123',
    //           'email' => 'landry.fof@gmail.com'
    //       ];
    //       $order_details = [
    //           'SKU' => 'D-123456',
    //           'price' => '10000',
    //           'order_date' => '2020-01-22',
    //       ];
  
    //       $sendmail = Mail::to($customer_details['email'])->send(new SendMail($title, $customer_details, $order_details));
    //       if (empty($sendmail)) {
    //           return response()->json(['message' => 'Mail Sent Sucssfully'], 200);
    //       }else{
    //           return response()->json(['message' => 'Mail Sent fail'], 400);
    //       }
    //   }


    public function sendEMail(){
		// $this->validate($request, [
	    //     'email' => 'bail|required|email',
    	// ]);
    	
		$beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
		$beautymail->send('mail.welcome', [], function($message) 
	    {
	    	$email = 'landry.fof@gmail.com';
	        $message
				->from('info@YetiBeauty.net')
				->to($email, 'Howdy buddy!')
				->subject('Test Mail!');
	    });
	   return response()->json(['message'=>'success']);
	}  
}
