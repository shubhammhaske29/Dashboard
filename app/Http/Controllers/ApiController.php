<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        try {
            $username = $request->get('username');
            $password = $request->get('password');

            $user = User::where('email', '=', $username)->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Invalid Username and Password']);
            }
            if (!Hash::check($password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid Username and Password']);
            }
            return response()->json(['success' => true, 'message' => 'success', 'data' => ['role' => config('common.user_ids')[$user->role_id], 'token' => $user->remember_token]]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

}
