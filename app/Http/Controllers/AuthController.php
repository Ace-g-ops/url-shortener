<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Registration logic here
        // validate the incoming requests
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|'
        ]);

        // create a request in the db
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        // create a toekn
        $token = $user->createToken('auth_token')->plainTextToken;  

        // return as a json response
        return response()->json([

            'user' => $user,
            'token' => $token 
        ], 201);
    }


    public function login(Request $request)
    {   
        // validate incominhg requests
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);         

        // check if user already exists
        $user = User::where('email', $validated['email'])->first();

        // return an eror if user dosent exist
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // create a new token after user logins
        $token = $user->createToken('auth_token')->plainTextToken;

        // return as a json response
        return response()->json([
            'user' => $user,
            'token' => $token 
        ],201);
    }
    public function logout(Request $request)
    {   
        // check for user existing token and delete
        $request->user()->currentAccessToken()->delete();

        // return as a json response

        return response()->json([

            'message' => 'Logged out successfully'
        
        ]);
    }
}
