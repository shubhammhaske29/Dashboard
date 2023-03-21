<?php

namespace App\Tpddl\Model;

use Illuminate\Database\Eloquent\Model;
use App\Tpddl\Helper\TpddlHelper;
use App\Tpddl\Helper\ApiHelper;

class ScheduleDrawal extends Model
{

    public static function getDrawalActual($startDate,$endDate)
    {
        date_default_timezone_set('Asia/Kolkata');
        
        $gettoken=ApiHelper::getToken();
        $token=$gettoken;
        $which_api='load';
        $which_func='getActual';

        $params_30_sec=array(
            'discom'=>'tpddl',
            'datatype'=>'30_sec',
            'fromdate'=>$startDate,
            'todate'=>$endDate,
            'client_id'=>30
         );

        $actual_30sec_data=ApiHelper::getData($token, $which_api, $which_func, $params_30_sec);
        $actual_30 = [];

        if(count($actual_30sec_data)!=0)
        {
            $actual = [];
            foreach ($actual_30sec_data as $value)
            {
                $actual[] = ['schedule'=>$value->drawl,'datetime'=>$value->datetime];
            }

            $actual_30 = TpddlHelper::getFifteenMinutesAverage($actual);
            $actual_30 = TpddlHelper::getTimestampValueArray($actual_30);
        }

        $params_urd=array(
            'discom'=>'tpddl',
            'datatype'=>'urd',
            'fromdate'=>$startDate,
            'todate'=>$endDate,
            'client_id'=>30
        );

        $actual_urd_data=ApiHelper::getData($token, $which_api, $which_func, $params_urd);
        $actual_urd = [];

        if(count($actual_urd_data)!=0)
        {
            $actual = [];
            foreach ($actual_urd_data as $value)
            {
                $date = strtotime($value->datetime)*1000;
                $actual[] = [$date,$value->load];
            }
            $actual_urd = $actual;
        }

        $response = array_replace($actual_30,$actual_urd);
        return $response;
    }
}