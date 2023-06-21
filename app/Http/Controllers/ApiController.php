<?php

namespace App\Http\Controllers;

use App\AssignToilets;
use App\Expense;
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
            return response()->json(['success' => true, 'message' => 'success', 'data' => ['user_id' => $user->id, 'role' => config('common.user_ids')[$user->role_id], 'token' => $user->token, 'vehicle_id' => $user->vehicle_id, 'assign_vehicle_date' => $user->assign_vehicle_date]]);

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
            $expense = Expense::getTodayExpense($vehicle_id);
            if (empty($expense)) {
                $expense = new Expense();
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
            $expense = Expense::getTodayExpense($vehicle_id);

            return response()->json(['success' => true, 'message' => 'success', 'data' => $expense]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function getToiletList(Request $request)
    {
        try {
            $vehicle_id = $request->get('vehicle_id');
            $user_type = $request->get('user_type');
            $user_id = $request->get('user_id');
            $toilets = AssignToilets::getAssignToiletsListByVehicleId($vehicle_id,$user_type,$user_id);

            return response()->json(['success' => true, 'message' => 'success', 'data' => $toilets]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function reportToilet(Request $request)
    {
        try {
            $toilet_id = $request->get('toilet_id');
            $assign_toilet = AssignToilets::find($toilet_id);

            if(true == is_null($assign_toilet)){
                //return response()->json(['success' => false, 'message' => 'Please Provide Correct Toilet Id']);
                return response()->json(['success' => false]);
            }
            $assign_toilet->is_reported_not_clean = 1;
            $assign_toilet->save();

            return response()->json(['success' => true, 'message' => 'success', 'data' => 'Toilet Reported not clean']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function completeToilet(Request $request)
    {
        try {
            $toilet_id = $request->get('toilet_id');
            $user_id = $request->get('user_id');
            $assign_toilet = AssignToilets::find($toilet_id);

            if(true == is_null($assign_toilet)){
                //return response()->json(['success' => false, 'message' => 'Please Provide Correct Toilet Id']);
                return response()->json(['success' => false]);
            }
            $assign_toilet->completed_by = $user_id;
            $assign_toilet->save();

            return response()->json(['success' => true, 'message' => 'success', 'data' => 'Toilet Mark as clean']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function getToiletCount(Request $request)
    {
        try {
            $vehicle_id = $request->get('vehicle_id');
            $complete_count = AssignToilets::getToiletCompleteCount($vehicle_id);
            $pending_count = AssignToilets::getToiletPendingCount($vehicle_id);
            $total = $complete_count + $pending_count;

            return response()->json(['success' => true, 'message' => 'success', 'data' => ['complete_count' => $complete_count, 'pending_count' => $pending_count, 'total' => $total]]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }


    public function uploadFile(Request $request)
    {
        if (!$request->hasFile('fileNames')) {
            //return response()->json(['success' => false, 'message' => 'File Not Found']);
            return response()->json(['success' => false]);
        }

        $allowedfileExtension = ['jpg', 'png'];
        $files = $request->file('fileNames');
        $id = $request->get('id');
        $type = $request->get('type');
        $assign_toilet = AssignToilets::find($id);

        if(true == is_null($assign_toilet)){
            //return response()->json(['success' => false, 'message' => 'Please Provide Correct Toilet Id']);
            return response()->json(['success' => false]);
        }
        $destinationPath = '';
        foreach ($files as $file) {

            $extension = $file->getClientOriginalExtension();

            $check = in_array($extension, $allowedfileExtension);

            if ($check) {
                $destinationPath = public_path() . '/Images/' . $id . '/';
                $file->move($destinationPath.$type, time() . '.' . $file->getClientOriginalExtension());
            } else {
                //return response()->json(['success' => false, 'message' => 'Invalid File Format']);
                return response()->json(['success' => false]);
            }
        }

        if ($destinationPath = '') {
            //return response()->json(['success' => false, 'message' => 'File Not Found']);
            return response()->json(['success' => false]);
        }

        $assign_toilet->image_path	= $destinationPath;
        $assign_toilet->save();

        //return response()->json(['success' => true, 'message' => 'file uploaded Successfully']);
        return response()->json(['success' => true]);

    }

    public function getUserInfo(Request $request)
    {
        try {
            $user_id = $request->get('user_id');

            $user = User::find($user_id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User Not Exist']);
            }
            return response()->json(['success' => true, 'message' => 'success', 'data' => ['user_id' => $user->id, 'role' => config('common.user_ids')[$user->role_id], 'vehicle_id' => $user->vehicle_id, 'assign_vehicle_date' => $user->assign_vehicle_date]]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function getVehicleName(Request $request)
    {
        try {
            $vehicle_id = $request->get('vehicle_id');
            $veicle = Vehicle::find($vehicle_id);
            if (!$veicle) {
                return response()->json(['success' => false, 'message' => 'Vehicle Not Exist']);
            }
            return response()->json(['success' => true, 'vehicle_number' => $veicle->number]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

}
