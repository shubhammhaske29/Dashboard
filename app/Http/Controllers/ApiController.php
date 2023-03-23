<?php

namespace App\Http\Controllers;

use App\User;
use App\Vehicle;
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
            return response()->json(['success' => true, 'message' => 'success', 'data' => ['role' => config('common.user_ids')[$user->role_id], 'token' => $user->token,'vehicle_id' => $user->vehicle_id,'assign_vehicle_date' => $user->assign_vehicle_date]]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function vehiclesList()
    {
        try {

            $vehiclesList = Vehicle::getNotAssignVehicleList();
            return response()->json(['success' => true, 'message' => 'success', 'data' => $vehiclesList]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

}
