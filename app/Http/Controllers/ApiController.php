<?php

namespace App\Http\Controllers;

use App\Expenses;
use App\User;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

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
            return response()->json(['success' => true, 'message' => 'success', 'data' => ['user_id' => $user->id,'role' => config('common.user_ids')[$user->role_id], 'token' => $user->token,'vehicle_id' => $user->vehicle_id,'assign_vehicle_date' => $user->assign_vehicle_date]]);

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

    public function assignVehicleToUser(Request $request)
    {
        try {
            $userId = $request->get('user_id');
            $vehicle_id = $request->get('vehicle_id');
            $user = User::find($userId);
            $user->vehicle_id = $vehicle_id;
            $user->assign_vehicle_date = date("Y-m-d");
            $user->update();
            return response()->json(['success' => true, 'message' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    protected function prepareExpense($request)
    {
        $data = new \stdClass();
        $data->vehicle_id = $request->get('vehicle_id');
        $data->expense_date = date("Y-m-d");
        $data->start_read = $request->get('start_read');
        $data->end_read = $request->get('end_read');
        $data->petrol_amount = $request->get('petrol_amount');
        $data->diesel_amount = $request->get('diesel_amount');
        $data->updated_by = $request->get('updated_by');
        return $data;
    }
    public function addExpense(Request $request)
    {
        try {
            $vehicle_id = $request->get('vehicle_id');
            $expense = Expenses::getTodayExpense($vehicle_id);
            if(empty($expense)){
                $expense = new Expenses();
            }
            $data = $this->prepareExpense($request);
            $expense->saveData($data);
            return response()->json(['success' => true, 'message' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function getExpense(Request $request)
    {
        try {
            $vehicle_id = $request->get('vehicle_id');
            $expense = Expenses::getTodayExpense($vehicle_id);

            return response()->json(['success' => true, 'message' => 'success', 'data' => $expense]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

}
