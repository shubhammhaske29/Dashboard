<?php

namespace App\Tpddl\Helper;
use Illuminate\Database\Eloquent\Model;

class TpddlHelper extends Model
{
    public static function getFifteenMinutesAverage($result)
    {
       $hours_array = [];

       $block_1_15  = [];

       $block_15_30  = [];

       $block_30_45  = [];

       $block_45_60  = [];

       $date_time_array = [];

       foreach($result as $row)
       {
           $date_time = $row['datetime'];
           
           $hour = date('H', strtotime($date_time));

           $min  = date('i', strtotime($date_time));

           $date = date("Y-m-d H:", strtotime($date_time));

           if(!in_array($hour, $hours_array))
           {
               $hours_array   = [];
               $hours_array[] = $hour;

               if(!empty($block_45_60))
               {
                   $response[] = self::getAverageValue($date_time_array[0],45,$block_45_60);
                   $block_45_60 = [];
               }

               $date_time_array   = [];
               $date_time_array[] = $date;

           }

           if(($min >= 00 && $min < 15))
           {
               $block_1_15[] = floatval($row['schedule']);
           }
           if(($min >= 15 && $min < 30))
           {
               if(!empty($block_1_15))
               {
                   $response[] = self::getAverageValue($date_time_array[0],00,$block_1_15);
                   $block_1_15 = [];
               }

               $block_15_30[] = floatval($row['schedule']);
           }
           if(($min >= 30 && $min < 45))
           {
               if(!empty($block_15_30))
               {
                   $response[] = self::getAverageValue($date_time_array[0],15,$block_15_30);
                   $block_15_30 = [];
               }
               $block_30_45[] = floatval($row['schedule']);
           }
           if(($min >= 45))
           {
               if(!empty($block_30_45))
               {
                   $response[] = self::getAverageValue($date_time_array[0],30,$block_30_45);
                   $block_30_45 = [];
               }
               $block_45_60[] = floatval($row['schedule']);
           }
       }
        switch ($min) 
        {
            case ($min >= 45):$response[] = self::getAverageValue($date_time_array[0],45,$block_45_60);
                              break;
            case ($min >= 30 && $min < 45):$response[] = self::getAverageValue($date_time_array[0],30,$block_30_45);
                              break;
            case ($min >= 15 && $min < 30):$response[] = self::getAverageValue($date_time_array[0],15,$block_15_30);
                              break;
            case ($min >= 00 && $min < 15):$response[] = self::getAverageValue($date_time_array[0],00,$block_1_15);
                              break;   
        }
    return $response; 
    }

    public static function getAverageValue($date,$time_block,$load_array)
    {
       $time = date("Y-m-d H:i",strtotime($date.$time_block));
       $avg  = round(array_sum($load_array)/count($load_array),2);

       $response = [$time,$avg];

       return $response;
    }

    public static function getTimeBlock()
    {
        $time_block = [];
        $m = ['00',15,30,45];
        for($i = 0; $i < 24; $i++)
        {
            $i = $i < 10 ? "0".$i : $i;
            for($j = 0; $j < 4; $j++)
            {
                $time_block[] = $i.":".$m[$j].":00";
            }
        }
        return $time_block;
    }

    public static function getFilledTimeBlockArrayIntraDay($array,$require_datetime,$time_block)
    {
        for ($j=0; $j < count($require_datetime) ; $j++) 
        { 
            $matchFound = 0;
            for ($k=0; $k < count($array); $k++) 
            { 
                if($require_datetime[$j] == $array[$k][0])
                {
                    $date_time = explode(' ', $array[$k][0]);
                    $key = array_search($date_time[1].":00", $time_block);
                    $timeblockFilledArray[] = ['datetime'=>$array[$k][0].'-'.$time_block[$key+1],'value'=>$array[$k][1]];
                    $matchFound = 1;break; 
                }
            }
            if($matchFound != 1)
                {
                    $date_time = explode(' ', $require_datetime[$j]);
                    $key = array_search($date_time[1], $time_block);
                    $timeblockFilledArray[] = ['datetime'=>$require_datetime[$j].'-'.$time_block[$key+1],'value'=>'Not Available'];
                }
        }
        
        return $timeblockFilledArray;
    }

    public static function getFilledTimeBlockArrayDayAhead($array,$time_block)
    {
        for ($i=0; $i < 96; $i++) 
        { 
            $keyFound = 0;        
            $time = substr($time_block[$i], 0,-3);
            for ($j=0; $j < count($array); $j++) 
            { 
                if(strpos($array[$j][0], $time))
                {
                    $timeblockFilledArray[] = ['datetime'=>$time_block[$i].'-'.$time_block[$i+1],'value'=>$array[$j][1]];
                    $keyFound = 1;
                    break;
                }
            }
            if($keyFound != 1)
            {
                $timeblockFilledArray[] = ['datetime'=>$time_block[$i].'-'.$time_block[$i+1],'value'=>'Not Available'];
            }
        }
        return $timeblockFilledArray;
    }

    public static function readJsonFile($jsonFileName)
    {
      if (file_exists($jsonFileName)) 
      {
          $fileData = file_get_contents($jsonFileName);
          $fileData = json_decode($fileData,true);
      }
      else
      {
          $fileData = array("date"=>"","previous_revision"=>"");
      }
      return $fileData;
    }

    public static function getTimestampValueArray($array)
    {
      $response = [];
      foreach ($array as $value)
        {
            $delivery_date = date('Y-m-d H:i',strtotime($value[0]));
            $date = strtotime($delivery_date)*1000;
            $response[] = [$date,(float)$value[1]];
        }
      return $response;
    }

    public static function makeData($actual_30,$actual_5)
    {
        date_default_timezone_set('Asia/Kolkata');

        $actual_data_30 = [];

        foreach ($actual_30 as $value)
        {
            $actual_data_30[$value[0]] = $value[1];
        }

        $actual_data_5 = [];

        foreach ($actual_5 as $value)
        {
            $actual_data_5[$value[0]] = $value[1];
        }
        $date = date("Y-m-d",strtotime("-1 days"));

        $data = [];

        for($d = 0; $d < 2; $d++)
        {
            $date = date("Y-m-d",strtotime($date." +".$d." days"));

            $m = ['00',15,30,45];
            for($i = 0; $i < 24; $i++)
            {
                $i = $i < 10 ? "0".$i : $i;
                for($j = 0; $j < 4; $j++)
                {
                    $datetime = $date." ".$i.":".$m[$j];

                    if(isset($actual_data_30[$datetime]))
                    {
                        $data[] = [$datetime,$actual_data_30[$datetime]];
                    }
                    elseif (isset($actual_data_5[$datetime]))
                    {
                        $data[] = [$datetime,$actual_data_5[$datetime]];
                    }
                }
            }
        }
        return $data;
    }
}
