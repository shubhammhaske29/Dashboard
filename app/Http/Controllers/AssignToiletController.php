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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;


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

        return $data;
    }

    public function index()
    {

        $data = AssignToilets::getAssignToiletsList();

        return view('assign_toilet.index')
            ->with('assign_toilets',$data);
    }

    public function report()
    {

        $data = AssignToilets::getReport();

        return view('report.index')
            ->with('assign_toilets',$data);
    }

    public function add(Request $request)
    {
        try
        {

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


            $arrToilets = $request->get('toilet_id');

            foreach ($arrToilets as $toiletId) {
                $assign_toilet = new AssignToilets();
                $data = $this->prepareData($request);
                $data->toilet_id = $toiletId;
                $assign_toilet->saveData($data);
            }


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

    public function download_report($id)
    {
        $fileName = '/tmp/'.$id.'.zip';
        $this->ZipCreate(storage_path() . '/Images/' . $id . '/', $fileName);

        return response()->download($fileName);
    }

    function ZipCreate($source, $destination)
    {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
        else if (is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

}
