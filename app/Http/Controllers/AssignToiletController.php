<?php

namespace App\Http\Controllers;

use App\AssignToilets;
use App\Toilet;
use App\User;
use App\UserChecker;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AssignToiletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function prepareData($request)
    {
        $data = new \stdClass();
        $date = strtotime($request->get('assign_date'));
        $data->assign_date = date('Y-m-d', $date);
        $data->vehicle_id = $request->get('vehicle_id');
        $data->cleaning_type_id = $request->get('cleaning_type_id');
        $data->zone = $request->get('zone');
        $data->ward = $request->get('ward');
        $data->toilet_id = $request->get('toilet_id');

        return $data;
    }

    public function index()
    {

        $data = AssignToilets::getAssignToiletsList();

        return view('assign_toilet.index')
            ->with('assign_toilets',$data);
    }

    public function add(Request $request)
    {
        try
        {
            $assign_toilet = new AssignToilets();

            if (!$request->isMethod('POST')) {
                $today = date("Y-m-d");
                $tomorrow = date("Y-m-d", strtotime("+1 days"));
                $vehicles = Vehicle::getVehicleList();
                $toilet_list = [];

                foreach (Toilet::getToiletLists($today) as $toilet){
                    $toilet_list[date("d-m-Y")][$toilet->ward][$toilet->id] = $toilet;
                }
                foreach (Toilet::getToiletLists($tomorrow) as $toilet){
                    $toilet_list[date("d-m-Y", strtotime("+1 days"))][$toilet->ward][$toilet->id] = $toilet;
                }

                return view('assign_toilet.add')
                    ->with('vehicles', $vehicles)
                    ->with('toilet_list', $toilet_list);
            }

            $validator = Validator::make($request->all(), [
                'vehicle_id'            => 'required|max:255',
                'toilet_id'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("assign_toilet"))->withErrors($validator)->withInput($request->all());
            }

            $data = $this->prepareData($request);

            $assign_toilet->saveData($data);

            $request->session()->flash('message', 'Toilet Assigned successfully');
            return Redirect::to(route("assign_toilet_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', $e->getMessage());
            return Redirect::to(route("assign_toilet_home"));
        }
    }

    public function delete($id, Request $request)
    {
        try {
            AssignToilets::deleteAssignToilet($id);
            $request->session()->flash('message', 'Assign Toilet Deleted successfully');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Something Went Wrong');
        }
        return Redirect::to(route("assign_toilet_home"));
    }
}
