<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Exception;
use Lcobucci\JWT\Parser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = User::all();

        if (!$res) {
            return $this->respondNotFound('User does not exists');
        }
        return $this->showAll($res);
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
            'name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
       
        

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'admin' => User::REGULAR_USER,
            'avatar' => 'avatar',
            'password' => bcrypt($request->password),
            'activation_token' => str_random(60)
        ]);
        $user->save();
        $user->notify(new SignupActivate($user));

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['data' => $user, 'access_token' => $accessToken],201);
    }


    public function signupActivate($token)
{
    $user = User::where('activation_token', $token)->first();
    if (!$user) {
        return response()->json([
            'message' => 'This activation token is invalid.'
        ], 404);
    }
    $user->active = true;
    $user->activation_token = '';
    $user->save();
    return $this->showOne($user);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = User::find($id);

        if (!$res) {
            throw new ModelNotFoundException('User not found by ID');
        }
        return $this->showOne($res);
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $data = User::findOrFail($id);

        if($request->file('image')) {
            try {
   
                $path = $request->file('image'); // Get file

                // Get the image and convert into string 
                $img = file_get_contents($request->file('image'));
    
                // Encode the image string data into base64 
                $fileName = base64_encode($img);
                $data->image= $fileName;


            } catch (Exception $e) {
                echo "catch";

            }
        }
       

          if ($request->has('email')) {
            $data->email = $request->email;
          }
    
          if ($request->has('password')) {
            $data->password = $request->password;
          }

          
    
         
          if (!$data->isDirty()) {
            return  $this->errorResponse('You need to specify a different value to update', 422);
          }
          $data->save();
          return $this->showOne($data);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::find($id);

        if(!$data){
            throw new ModelNotFoundException('User not found by ID');
      
        }
        
        $data->Delete(); 
        
        
        return $this->showOne($data);
    }



     /**
     * login.
     *
     * @param  int  email,password
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;
        if(!Auth::attempt($credentials))
            return $this->errorResponse('Unauthorized', 401);
        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json(['datat'=>[
            'access_token' => $tokenResult->accessToken,
            'id'=>$user['id'],
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]],200);
          
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();
    
            return response()->json([
              'success' => true,
              'message' => 'You have been successfully logged out!'
            ],200);
          }else {
            return response()->json([
              'success' => false,
              'message' => 'Unable to Logout'
            ],400);
          }
    }





public function change_password(Request $request)
{
    $input = $request->all();
    $userid = Auth::guard('api')->user()->id;
    $rules = array(
        'old_password' => 'required',
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|same:new_password',
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
            } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
            } else {
                User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
            }
        } catch (\Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            $arr = array("status" => 400, "message" => $msg, "data" => array());
        }
    }
    return Response()->json($arr);
}

}
