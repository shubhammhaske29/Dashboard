<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Cache;

use App\Tpddl\Helper\TpddlHelper;
use App\Tpddl\Helper\ApiHelper;
use App\Tpddl\Model\ScheduleDrawal;
use App\Tpddl\Model\UrdActualDemand;

use App\Tpddl\Model\DayAheadForecast;

use App\Tpddl\Model\DayaheadAccuracy;

use App\Tpddl\Model\ScheduleGeneration;

use App\Tpddl\Model\WeatherCombiner;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return responsese
     */
    public function index()
    {
        //---old code changed 31-08-18----//return view('home');
        return view('dayahead');
    }

    public function dayAhead()
    {
        return view('dayahead');
    }

    public function changePassword(Request $request)
    {
        if($request->isMethod('post'))
        {
            $user_password = User::where('id',Auth::user()->id)->pluck('password')->first();

            $old_password = $request->get('old_password');

            if(Hash::check($old_password, $user_password))
            {
                $new_password = $request->get('password');

                $confirm_password = $request->get('password_confirmation');

                if($new_password == $confirm_password)
                {
                    User::where('id',Auth::user()->id)
                        ->update(['password' => bcrypt($new_password)]);

                    $request->session()->flash('message', "Password Changed Successfully.");
                }
                else
                {
                    $request->session()->flash('error', "Password and Confirm password doesn't Match.");
                }
            }
            else
            {
                $request->session()->flash('error', "Old Password doesn't Match.");
            }
            return Redirect::to(route("change-password"));
        }

        return view('changepassword');
    }

   
    public function dayAheadGraph()
    {
        date_default_timezone_set('Asia/Kolkata');
        $startDate = date("Y-m-d",strtotime("-1 days"));
        $endDate = date("Y-m-d",strtotime("+2 days"));
        $data = [];
        
        $UrdForecastKey = "UrdForecastKey-".$startDate."-".$endDate;
        
        $data['Actual'][] = ['data'=>ScheduleDrawal::getDrawalActual($startDate,$endDate)];
        
        /*if (Cache::has($UrdForecastKey))
        {
            $UrdForecastResponse = Cache::get($UrdForecastKey);
            $data['UrdForecast'][] = ['data'=>$UrdForecastResponse['data']]; 
        }
        else
        {*/
            $UrdForecastResponse = DayAheadForecast::getUrdDayAheadForecast($startDate,$endDate);
            $data['UrdForecast'][] = ['data'=>$UrdForecastResponse['data']]; 
        //}
        return response()->json([
            "status"    => "success",
            "data"      => $data
        ]);
    }
   

    public function lastThreeDaysUrdForecastExcel($startDate,$endDate)
    {
        $data['urdActual'] = UrdActualDemand::getUrdActual($startDate,$endDate);
        $UrdForecastResponse = DayAheadForecast::getUrdDayAheadForecast($startDate,$endDate);
        $data['urdForecast'] = $UrdForecastResponse['data'];
         
        return response()->json([
            "status"    => "success",
            "data"      => $data
        ]);
    }


    public function urdDayAheadForecastExcel($startDate,$endDate)
    {
        $user = Auth::user()->name;
        $action = "Urd Day Ahead Forecast Report Download";

        $data = DayAheadForecast::getUrdDayAheadForecast($startDate,$endDate);
        if(count($data)>0)
            {
                return response()->json([
                "status"    => "success",
                "data"      => $data
                ]);
            }
        else
            {
                return response()->json([
                "status"    => "error",
                "msg"      => "no-data"
                ]);
            }
    }

    public function dayAheadayAccuracySummary()
    {
        $data = [];

        $dayAheadAccuracySummaryKey = "dayAheadAccuracySummaryKey";
        if (Cache::has($dayAheadAccuracySummaryKey))
        {
            $data = Cache::get($dayAheadAccuracySummaryKey);
        }
        else
        {
            $data  = DayaheadAccuracy::selectData();
        }

        return response()->json([
            "status"    => "success",
            "data"      => $data
        ]);
    }

    public function dayAheadScheduleForecastTable()
    {
        date_default_timezone_set('Asia/Kolkata'); 
        $response = [];
        $startDate = date("Y-m-d",strtotime("-1 days"));
        $endDate = date("Y-m-d",strtotime("-1 days"));

        $data['Schedule'][] = ['data'=>ScheduleGeneration::getSchedule($startDate,$endDate)];
        $data['UrdActual'][] = ['data'=>UrdActualDemand::getUrdActual($startDate,$endDate)];
        $data['UrdForecast'][] = DayAheadForecast::getUrdDayAheadForecast($startDate,$endDate);

        return response()->json([
            "status"    => "success",
            "data"      => $data
        ]);
    }

    public function getWeatherCombinerGraphData($startDate,$endDate)
    {
         $WeatherCombinerKey = "WeatherCombiner-".$startDate."-".$endDate;
         //if (Cache::has($WeatherCombinerKey))
         //{
         //    $data['WeatherCombiner'][] = Cache::get($WeatherCombinerKey);
         //}
         //else
         //{
            $data['WeatherCombiner'][] = WeatherCombiner::getWeatherCombinerGraphData($startDate,$endDate);   
         //}
         
        return response()->json([
            "status"    => "success",
            "data"      => $data
        ]);
    }

}
