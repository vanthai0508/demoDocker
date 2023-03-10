<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // register
    public function signup(Request $request)
    {
        dd('thai');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 1,
        ]);
        $user->save();
        return response()->json([
            'data' => $request->all(),
            'status' => 'success',           
            'token_type' => 'Bearer',           
        ]);
    } 
    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
 
        if ($validator->fails()) 
        {
            return response()->json([
                'status' => __('message.fails'),
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ]);
        }
 
        $credentials = request(['email', 'password']);
 
        if (!Auth::attempt($credentials)) 
        {
            return response()->json([
                'status' => __('message.fails'),
                'message' => "Sai tài khoản hoặc mật khẩu"
            ]);  
        }
        else
        {
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
    
            if ($request->remember_me) 
            {
                $token->expires_at = Carbon::now()->addWeeks(1); 
            }
    
            $token->save();
            
            return response()->json([
                'status' => __('message.success'),
                'name' => $user -> name,
                'email' => $user -> email,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);

        }
    }
 

    
    // logout
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => __('message.success'),
        ]);
        
    }
 
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    //phpcs --standard=PSR12 App\Http\Controllers\AuthController.php
}