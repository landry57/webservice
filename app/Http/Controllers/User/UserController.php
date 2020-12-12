<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravolt\Avatar\Facade as Avatar;

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
            'fullname' => 'required|string',
            'phone' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $avatar = '';
        if ($files = $request->file('avatar')) {
            $destinationPath = 'uploads/avatar/'; // upload path
            $profilefile = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profilefile);
            $avatar = $destinationPath . "$profilefile";
        }

        $link ='uploads/avatars/'.date('YmdHis') . '.avatar.png';
        //$link ='uploads/avatar20201031140617.file.png';
         Avatar::create(User::getNameAttribute($request->fullname))->save($link, $quality = 100);
        
        $user = new User([
            'fullname' => User::getNameAttribute($request->fullname),
            'email' => $request->email,
            'phone' => $request->phone,
            'admin' => User::REGULAR_USER,
            'avatar' =>  $avatar ? $avatar : $link,
            'password' => bcrypt($request->password),
            'activation_token' =>  str::random(60)
        ]);
        $user->save();
        $user->notify(new SignupActivate($user));

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['data' => $user, 'access_token' => $accessToken], 201);
    }


    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->status = true;
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
    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);


        if ($request->has('status')) {
            $data->status = $request->status;
        }

        if ($request->has('email')) {
            $data->email = $request->email;
        }
        if ($request->has('phone')) {
            $data->phone = $request->phone;
        }
        if ($request->has('fullname')) {
            $data->fullname =  User::getNameAttribute($request->fullname);
        }

       
        if (!$data->isDirty()) {
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
        $data = User::find($id);

        if (!$data) {
            throw new ModelNotFoundException('User not found by ID');
        }
        if (File::exists(public_path($data->avatar))) {
            File::delete($data->avatar);
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
        $credentials['status'] = 1;
      
        if (!Auth::attempt($credentials))
            return $this->errorResponse('Unauthorized', 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json(['data' => [
            'access_token' => $tokenResult->accessToken,
            'id' => $user['id'],
            'fullname' => $user['fullname'],
            'admin' => $user['admin'],
            'avatar' => $user['avatar'],
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]], 200);
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
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ], 400);
        }
    }





    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("message" => $validator->errors()->first());
            return Response()->json($arr, 400);
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("message" => "Check your old password.");
                    return Response()->json($arr, 400);
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("message" => "Please enter a password which is not similar then current password.");
                    return Response()->json($arr, 400);
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);

                    $arr = array("message" => "Password updated successfully.");
                    return Response()->json($arr, 200);
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("message" => $msg);
                return Response()->json($arr, 400);
            }
        }
    }
}
