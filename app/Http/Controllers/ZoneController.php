<?php

namespace App\Http\Controllers;

use App\User;
use App\UserChecker;
use App\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function prepareData($request)
    {
        $data               = new \stdClass();
        $data->zone         = $request->get('zone');
        $data->ward         = $request->get('ward');
        return $data;
    }

    public function index()
    {

        $wards = Ward::all();

        return view('ward.index')
            ->with('wards',$wards);
    }

    public function add(Request $request)
    {
        try
        {
            $user_checker = new Ward();

            if(!$request->isMethod('POST'))
            {
                return view('ward.add');
            }

            $validator = Validator::make($request->all(), [
                'zone'            => 'required|max:255',
                'ward'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("add_ward"))->withErrors($validator)->withInput($request->all());
            }

            $checker_data = $this->prepareData($request);

            $user_checker->saveData($checker_data);

            $request->session()->flash('message', 'Ward Added successfully');
            return Redirect::to(route("ward_home"));
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Ward is already exist : ' . $user_checker->ward);
            return Redirect::to(route("add_ward"));
        }
    }

    public function edit($id, Request $request)
    {
        try
        {
            if(!$request->isMethod('POST'))
            {
                $ward = Ward::find($id);
                return view('checker.edit')
                    ->with('ward',$ward);
            }

            $validator = Validator::make($request->all(), [
                'zone'            => 'required|max:255',
                'ward'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("edit_ward",$id))->withErrors($validator)->withInput($request->all());
            }

            $checker_data = $this->prepareData($request);

            $user_checker = new Ward();
            $user_checker->updateData($checker_data,$id);

            $request->session()->flash('message', 'Checker Data Updated successfully');
            return Redirect::to(route("user_checker_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
            return Redirect::to(route("edit_user_checker",$id));
        }
    }

    public function delete($id, Request $request)
    {
        try
        {
            Ward::deleteWard($id);
            $request->session()->flash('message', 'Ward Deleted successfully');
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
        }
        return Redirect::to(route("ward_home"));
    }
}
