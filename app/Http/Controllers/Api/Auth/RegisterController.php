<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages()->all(), 400);
        }

        //check user already exit
        $user = User::where('email', $request->email)->first();

        if (empty($user)) {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('Api token')->accessToken;
            return response()->json([
                'data' => $success,
                'status' => true,
                'message' => 'login, welcome',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Already login',
            ], 200);
        }

    }
}
