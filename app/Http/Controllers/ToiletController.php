<?php

namespace App\Http\Controllers;

use App\Toilet;
use App\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ToiletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function prepareData($request)
    {
        $data               = new \stdClass();
        $data->zone         = $request->get('zone');
        $data->ward        = $request->get('ward');
        $data->name        = $request->get('name');
        $data->number        = $request->get('number');
        $data->address        = $request->get('address');
        $data->latitude        = $request->get('latitude');
        $data->longitude        = $request->get('longitude');
        return $data;
    }

    public function index()
    {

        $toilets = Toilet::getToiletList();

        return view('toilet.index')
            ->with('toilets',$toilets);
    }

    public function add(Request $request)
    {
        try
        {
            $toilet = new Toilet();
            $zones = [];
            foreach (Ward::all() as $ward) {
                $zones[$ward->zone][] = $ward->ward;
            }
            if(!$request->isMethod('POST'))
            {
                return view('toilet.add')
                    ->with('zones', $zones);
            }

            $validator = Validator::make($request->all(), [
                'zone' => 'required|max:255',
                'ward' => 'required|max:255',
                'name' => 'required|max:255',
                'number' => 'required|max:255',
                'address' => 'required|max:255',
                'latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("add_toilet"))->withErrors($validator)->withInput($request->all());
            }

            $toilet_data = $this->prepareData($request);

            $toilet->saveData($toilet_data);

            $request->session()->flash('message', 'Toilet Added successfully');
            return Redirect::to(route("toilet_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', $e->getMessage());
            return Redirect::to(route("add_toilet"));
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
                $toilet = Toilet::find($id);
                return view('toilet.edit')
                    ->with('zones', $zones)
                    ->with('toilet',$toilet);
            }

            $validator = Validator::make($request->all(), [
                'zone'            => 'required|max:255',
                'ward'            => 'required|max:255',
                'name'            => 'required|max:255',
                'number'            => 'required|max:255',
                'address'            => 'required|max:255',
                'latitude'            => 'required|max:255',
                'longitude'            => 'required|max:255'
            ]);

            if ($validator->fails())
            {
                return Redirect::to(route("edit_toilet",$id))->withErrors($validator)->withInput($request->all());
            }

            $toilet_data = $this->prepareData($request);

            $toilet = new Toilet();
            $toilet->updateData($toilet_data,$id);

            $request->session()->flash('message', 'Toilet Updated successfully');
            return Redirect::to(route("toilet_home"));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
            return Redirect::to(route("edit_toilet",$id));
        }
    }

    public function delete($id, Request $request)
    {
        try
        {
            Toilet::deleteToilet($id);
            $request->session()->flash('message', 'Toilet Data Deleted successfully');
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', 'Something Went Wrong');
        }
        return Redirect::to(route("toilet_home"));
    }
}
