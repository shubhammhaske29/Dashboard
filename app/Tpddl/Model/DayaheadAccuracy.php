<?php

namespace App\Tpddl\Model;

use Illuminate\Database\Eloquent\Model;
use App\Tpddl\Helper\TpddlHelper;
use App\Tpddl\Helper\ApiHelper;

use \Cache;

class DayaheadAccuracy extends Model
{

    public static function selectData()
    {
    	date_default_timezone_set('Asia/Kolkata');

        $gettoken=ApiHelper::getToken();
        $token=$gettoken;
        $which_api='load';
        $which_func='getAccuracy';
        $startDate = date("Y-m-d",strtotime("-7 days"));
        $endDate = date("Y-m-d",strtotime("-1 days"));

        $params=array(
            'fromdate'=>$startDate,
            'todate'=>$endDate,
            'type'=>'dayahead',
            'model'=>'live',
            'client_id'=>30
        );

        $getData=ApiHelper::getData($token, $which_api, $which_func, $params);
        $response = [];
        $actual = [];

        if(count($getData)!=0)
        {
            foreach ($getData as $value) 
            {
               $response[] = ['ae'=>$value->ae,'avg_load'=>$value->avg_load,'date'=>$value->date];
            }
            $dayAheadAccuracySummaryKey = "dayAheadAccuracySummaryKey";
            Cache::put($dayAheadAccuracySummaryKey, $response, 15);
        }
        return $response;
    }
}
