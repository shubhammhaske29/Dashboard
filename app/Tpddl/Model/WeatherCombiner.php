<?php
namespace App\Tpddl\Model;

use Illuminate\Database\Eloquent\Model;
use App\Tpddl\Helper\TpddlHelper;
use App\Tpddl\Helper\ApiHelper;

use \Cache;

class WeatherCombiner extends Model
{
    public static function getWeatherCombinerGraphData($startDate,$endDate)
    {
        date_default_timezone_set('Asia/Kolkata'); 
        $response = [];
        
        $gettoken=ApiHelper::getToken();
        $token=$gettoken;
        $which_api='weather';
        $which_func='getForecast';

        $params=array(
            'client_id'=>30,
            'source'=>'combiner_forecast',
            'plant-id'=>1,
            'from-date'=>$startDate,
            'to-date'=>$endDate,
        );

        $weatherData=ApiHelper::getData($token, $which_api, $which_func, $params);

        if(count($weatherData)!=0)
            {
                foreach ($weatherData as $value) 
                {
                    $dateTime = date('Y-m-d H:i',strtotime($value->datetime_local));
                    $dateTime = strtotime($dateTime)*1000;

                    $response['apparent_temperature'][] = [$dateTime,$value->apparent_temperature];
                    $response['humidity'][] = [$dateTime,$value->humidity];   
                }
                $key = "WeatherCombiner-".$startDate."-".$endDate;
                Cache::put($key, $response, 15);
            }

        
        return $response;
    }
}
