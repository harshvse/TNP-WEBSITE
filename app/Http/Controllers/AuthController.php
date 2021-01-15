<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FormStatus;
use App\Models\StepTwo;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Providers\JWTAuthServiceProvider;
use JWTAuth;

class AuthController extends Controller
{
    public function registerStudent(Request $request)
    {
        $users= $request->data;
        $data=[];
        foreach($users["data"] as $user){
            $user['uuid']=(string) Str::uuid();
            $user['role_id']=1;
            $user['email']="";
            $user['username']=$user['urn'];
            $user['password']=bcrypt($user['crn']);
            $newUser = User::create($user);
            if($newUser){ 
                $form_user= formStatus::create([
                'user_id' => $newUser->id

                ]);
                $form_step_two = StepTwo::create([
                    'user_id' => $newUser->id,
                    'urn' => $user['urn'],
                    'crn' => $user['crn']
                ]);
                }

            }
        
        return response()->json(["message"=>"data added successfully"]);
    }

    public function registerAdmin(Request $request){

        $user=$request->data;
        $newAdmin=User::create([
            'role_id'=> 2,
            'username'=> $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'uuid' => (String) Str::uuid()
        ]);
        return response()->json(["message"=>"admin added successfully"]);
    }

    public function login(Request $request){
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);
    $token = JWTAuth::attempt(['username'=>$request->username, 'password'=>$request->password]);

    if (! $token ) {
        return response()->json(['error' => 'invalid_credentials'], 401);
    }

    $currentUser = Auth::user();

    return $this->respondWithToken($token,$currentUser);

}
public function logout()
{
    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);
}
protected function respondWithToken($token, $currentUser)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60,
        'current_user' => $currentUser
    ]);
}
}