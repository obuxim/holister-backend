<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Register and send back token
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'string|required',
            'profile_picture' => 'nullable|file',
            'password' => 'string|required|confirmed'
        ]);
        $user = User::create([
            'email' => $fields['email'],
            'name' => $fields['name'],
            'phone' => $fields['phone'],
            'gender' => $fields['gender'],
            'password' => Hash::make($fields['password']),
        ]);
        if($request->hasFile('profile_picture')){
            $originalFileName = $request->file('profile_picture')->getClientOriginalName();
            $fileExt = $request->file('profile_picture')->getClientOriginalExtension();
            $originalFileNameWithoutExt = Str::of($originalFileName)->basename('.'.$fileExt);
            $fileNameToSave = $originalFileNameWithoutExt . '_' . time() . '.' . $fileExt;
            $user->profile_picture = 'profile_picture/'.$fileNameToSave;
            $user->save();
            $request->file('profile_picture')->storeAs('public/profile_picture', $fileNameToSave);
        }

        $response = new \stdClass();
        $response->user = $user;
        $response->token = $user->createToken(config('app.key'))->plainTextToken;

        return response()->json($response);
    }

    // Login
    public function login (Request $request)
    {
        $fields = $request->validate([
            'email' => 'string|required',
            'password' => 'string|required',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || Hash::check($fields['password'], $user->password)){
            return response()->json(['message' => 'wrong credentials'], 401 );
        }

        $token = $user->createToken(config('app.key'))->plainTextToken;

        $response = new \stdClass();
        $response->user = $user;
        $response->token = $token;

        return response()->json($response, 200);
    }
}
