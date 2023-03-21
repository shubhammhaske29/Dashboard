<?php

namespace App\Tpddl\Helper;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ApiHelper extends Model
{

	public static function getToken()
		{
			try 
			{
				$client = new Client();
	            $request = $client->post(
	                'http://apihub.climate-connect.com/api/get-token',
	                [
	                    'form_params' => [
	                        'username' => 'tech@climate-connect.com',
	                        'password' => 'dentintheuniverse'
	                    ]
	                ]
	            );
	            $response = json_decode($request->getBody());
	            
	            if($response->status == "success"){
				
	                return $response->access_token;
	            }
	            else{
	            	
	            	 self::generateToken();
	                 
	            }
	        }
	        catch(\Exception  $e){
	            echo $e->getMessage();
	        }

		}


    public static function generateToken()
		{
			try 
			{
				
				$client = new Client();
	            $request = $client->post(
	                'http://apihub.climate-connect.com/api/generate-token',
	                [
	                    'form_params' => [
	                        'username' => 'tech@climate-connect.com',
	                        'password' => 'dentintheuniverse'
	                    ]
	                ]
	            );
	            $response = json_decode($request->getBody());
	            if($response->status == "success"){
	            	
	                return $response->access_token;
	            }
	            else{
	            	
	                return null;
	            }
	        }

	        catch(\Exception  $e){
	            echo $e->getMessage();
	        }

	       
		}


	public static function getData($token, $which_api, $which_func, $params)
    {
        
        if($token!=null) {
        	$client = new Client();
        	$urldata="";
	        $new_m = '';
			
		    foreach ($params as $key => $value) {
		        $new_m .= $key.'='.$value.'&';
		    } 
						
			$urldata=substr($new_m,0,-1);
	        	
        	$url='http://apihub.climate-connect.com/api'.'/'.$which_api.'/'.$which_func.'?'.$urldata;

            $response = $client->request('GET',$url , [
                'headers' => ['token' => $token],
                'Content-Type' => 'application/x-www-form urlencoded'
            ]);
             $response = json_decode($response->getBody());
             return $response->data;
        }
    }
}


?>