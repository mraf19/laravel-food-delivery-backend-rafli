<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //user register
    public function user_register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['roles'] = 'user';
        $user = User::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User Registered successfully',
            'data' => $user
        ]);
    }

    //login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ( !$user || !Hash::check($request->password, $user->password) ){
            return response(401)->json([
                'status' => 'failed',
                'message' => 'Invalid Credentials'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'login success',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout success'
        ]);
    }

    // restaurant register
    public function restaurant_register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'restaurant_name' => 'required|string',
            'restaurant_address' => 'required|string',
            'photo' => 'required|image',
            'latlong' => 'required|string',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['roles'] = 'restaurant';

        $user = User::create($data);

        //check if photo is uploaded
        if($request->hasFile('photo')){
            $photo = $request->file('photo');
            $ext = $photo->getClientOriginalExtension();
            $photo_name = time().'.'.$ext;
            $photo->move(public_path('images'), $photo_name);
            $user->photo = $photo_name;
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant Registered successfully',
            'data' => $user
        ]);

    }

    //driver register
    public function driver_register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'photo' => 'required|image',
            'license_plate' => 'required|string',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['roles'] = 'driver';

        $user = User::create($data);

        //check if photo is uploaded
        if($request->hasFile('photo')){
            $photo = $request->file('photo');
            $ext = $photo->getClientOriginalExtension();
            $photo_name = time().'.'.$ext;
            $photo->move(public_path('images'), $photo_name);
            $user->photo = $photo_name;
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Driver Registered successfully',
            'data' => $user
        ]);

    }

    // get all user
    public function get_all_user()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'message' => 'All users',
            'data' => $users
        ]);
    }
}

