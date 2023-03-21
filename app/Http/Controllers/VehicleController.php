<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function prepareData($request)
    {
        $vehicle               = new \stdClass();
        $vehicle->number         = $request->get('number');
        return $vehicle;
    }

    public function index()
    {

        $vehicles = Vehicle::getVehicleList();

        return view('vehicle.index')
            ->with('vehicles',$vehicles);
    }

    public function add(Request $request)
    {
        try
        {
            $vehicle = new Vehicle();

            if(!$request->isMethod('POST'))
            {
                return view('vehicle.add');
            }

            $validator = Validator::make($request->all(), [
                'number'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("add_vehicle"))->withErrors($validator)->withInput($request->all());
            }

            $vehicle_data = $this->prepareData($request);

            $vehicle->saveData($vehicle_data);

            $request->session()->flash('message', 'Vehicle Data saved successfully');
            return Redirect::to(route("vehicle_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', $e->getMessage());
            return Redirect::to(route("add_vehicle"));
        }
    }

    public function edit($id, Request $request)
    {
        try
        {
            if(!$request->isMethod('POST'))
            {
                $vehicle = Vehicle::find($id);
                return view('vehicle.edit')
                    ->with('vehicle',$vehicle);
            }

            $validator = Validator::make($request->all(), [
                'number'            => 'required|max:255',
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("edit_vehicle",$id))->withErrors($validator)->withInput($request->all());
            }

            $vehicle_data = $this->prepareData($request);

            $vehicle = new Vehicle();
            $vehicle->updateData($vehicle_data,$id);

            $request->session()->flash('message', 'Vehicle Data Updated successfully');
            return Redirect::to(route("vehicle_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
            return Redirect::to(route("edit_vehicle",$id));
        }
    }

    public function delete($id, Request $request)
    {
        try
        {
            Vehicle::deleteVehicle($id);
            $request->session()->flash('message', 'Vehicle Deleted successfully');
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
        }
        return Redirect::to(route("vehicle_home"));
    }
}
