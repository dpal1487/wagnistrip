<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function isLogin()
    {
        if (Auth::check()) {
            // User is logged in
            $user = Auth::user();
            // Get user details           
            // Return user details or do something else
            return response()->json([
                'data' => $user,
                'status' =>true
            ]);
        } else {
            // User is not logged in
            return response()->json([
                'message' => 'User is not logged in',
                'status' => false
            ], 401); // Unauthorized status code
        }
    }
}
