<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function prepareData($request)
    {
        $timestamp = \Carbon\Carbon::now();
        $user = new \stdClass();
        $user->name = $request->get('name');
        $user->email = $request->get('name');
        $user->role_id = $request->get('role_id');
        $user->password = bcrypt($request->get('password'));
        $user->token = bcrypt('admin@gmail.com' . $timestamp);
        return $user;
    }

    public function index()
    {

        $users = User::getUserList();

        return view('user.index')
            ->with('users', $users);
    }

    public function add(Request $request)
    {
        try {
            $user = new User();

            if (!$request->isMethod('POST')) {
                return view('user.add');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'role_id' => 'required',
                'password' => array(
                    'required',
//                    'min:8',
//                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@$!%*?&]/'
                )
            ]);

            if ($validator->fails()) {
                return Redirect::to(route("add_user"))->withErrors($validator)->withInput($request->all());
            }

            $user_data = $this->prepareData($request);

            $user->saveData($user_data);

            $request->session()->flash('message', 'User Data saved successfully');
            return Redirect::to(route("user_home"));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return Redirect::to(route("add_user"));
        }
    }

    public function edit($id, Request $request)
    {
        try {
            if (!$request->isMethod('POST')) {
                $user = User::find($id);
                return view('user.edit')
                    ->with('user', $user);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'role_id' => 'required',
                'password' => array(
                    'required',
//                    'min:8',
//                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@$!%*?&]/'
                ),
                'confirm_password' => 'required_with:password|same:password'
            ]);

            if ($validator->fails()) {
                return Redirect::to(route("edit_user", $id))->withErrors($validator)->withInput($request->all());
            }

            $user_data = $this->prepareData($request);

            $user = new User();
            $user->updateData($user_data, $id);

            $request->session()->flash('message', 'User Data Updated successfully');
            return Redirect::to(route("user_home"));
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Something Went Wrong');
            return Redirect::to(route("edit_user", $id));
        }
    }

    public function delete($id, Request $request)
    {
        try {
            User::deleteUser($id);
            $request->session()->flash('message', 'User Deleted successfully');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Something Went Wrong');
        }
        return Redirect::to(route("user_home"));
    }
}
