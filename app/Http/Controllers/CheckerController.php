<?php

namespace App\Http\Controllers;

use App\User;
use App\UserChecker;
use App\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CheckerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function prepareData($request)
    {
        $data               = new \stdClass();
        $data->user_id         = $request->get('user_id');
        $data->zone         = $request->get('zone');
        $data->ward         = $request->get('ward');
        return $data;
    }

    public function index()
    {

        $checkers = UserChecker::getUserCheckerList();

        return view('checker.index')
            ->with('checkers',$checkers);
    }

    public function add(Request $request)
    {
        try
        {
            $user_checker = new UserChecker();
            $zones = [];
            foreach (Ward::all() as $ward) {
                $zones[$ward->zone][] = $ward->ward;
            }
            if(!$request->isMethod('POST'))
            {
                $users = UserChecker::getUsersForCheckerAssignment();
                return view('checker.add')
                    ->with('zones', $zones)
                    ->with('users',$users);
            }

            $validator = Validator::make($request->all(), [
                'user_id'            => 'required|max:255',
                'zone'            => 'required|max:255',
                'ward'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("add_user_checker"))->withErrors($validator)->withInput($request->all());
            }

            $checker_data = $this->prepareData($request);

            $user_checker->saveData($checker_data);

            $request->session()->flash('message', 'Checker Assigned successfully');
            return Redirect::to(route("user_checker_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', $e->getMessage());
            return Redirect::to(route("add_user_checker"));
        }
    }

    public function edit($id, Request $request)
    {
        try
        {
            $zones = [];
            foreach (Ward::all() as $ward) {
                $zones[$ward->zone][] = $ward->ward;
            }
            if(!$request->isMethod('POST'))
            {
                $user_checker = UserChecker::find($id);
                $user = User::find($user_checker->user_id);
                return view('checker.edit')
                    ->with('user_checker',$user_checker)
                    ->with('zones', $zones)
                    ->with('user',$user);
            }

            $validator = Validator::make($request->all(), [
                'user_id'            => 'required|max:255',
                'zone'            => 'required|max:255',
                'ward'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("edit_vehicle",$id))->withErrors($validator)->withInput($request->all());
            }

            $checker_data = $this->prepareData($request);

            $user_checker = new UserChecker();
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
            UserChecker::deleteUserChecker($id);
            $request->session()->flash('message', 'Checker Data Deleted successfully');
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
        }
        return Redirect::to(route("user_checker_home"));
    }
}
