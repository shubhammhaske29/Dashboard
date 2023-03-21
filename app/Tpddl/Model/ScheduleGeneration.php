<?php

namespace App\Tpddl\Model;

use Illuminate\Database\Eloquent\Model;
use App\Tpddl\Helper\TpddlHelper;
use App\Tpddl\Helper\ApiHelper;

class ScheduleGeneration extends Model
{
    public static function getSchedule($startDate,$endDate)
    {
        date_default_timezone_set('Asia/Kolkata');

        $gettoken=ApiHelper::getToken();
        $token=$gettoken;
        $which_api='load';
        $which_func='getScheduleGeneration';

        $params=array(
            'fromdate'=>$startDate,
            'todate'=>$endDate,
            'client_id'=>30
         );

        $getData=ApiHelper::getData($token, $which_api, $which_func, $params);
        $response = [];
        $actual = [];

        if(count($getData)!=0)
        {
            foreach ($getData as $value) 
            {
               $response[] = ['schedule'=>$value->schedule,'datetime'=>$value->datetime];
            }
        }

        return $response;
    }
}
