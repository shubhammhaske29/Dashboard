<?php

namespace App\Tpddl\Model;

use Illuminate\Database\Eloquent\Model;
use App\Tpddl\Helper\TpddlHelper;
use App\Tpddl\Helper\ApiHelper;
use \Cache;


class DayAheadForecast extends Model
{

	public static function getUrdDayAheadForecast($startDate,$endDate)
    {
        date_default_timezone_set('Asia/Kolkata'); 
        $response = [];
        
        $gettoken=ApiHelper::getToken();
        $token=$gettoken;
        $which_api='load';
        $which_func='getForecast';

        $params=array(
            'client_id'=>30,
            'source'=>'urd',
            'fromdate'=>$startDate,
            'todate'=>$endDate,
            'type'=>'dayahead',
            'revision_no'=>1,
	    'model'=>'live'
        );


        $getData=ApiHelper::getData($token, $which_api, $which_func, $params);
        
        $actual = [];

         if(count($getData)!=0)
            {
                foreach ($getData as $value) 
                {
                    $dateValue=date("Y-m-d", strtotime($value->date));
                    $start_time=$value->start_time;
                    $delivery_date = date('Y-m-d H:i',strtotime($dateValue.$value->start_time));
                    $date = strtotime($delivery_date)*1000;
                    $response[] = [$date,round($value->forecast,2)];

                }
                $response = ['data'=>$response,'created_at'=>$value->created_at];

                $UrdForecastKey = "UrdForecastKey-".$startDate."-".$endDate;
                Cache::put($UrdForecastKey, $response, 15);
            }

        return $response;
    }

    
}
