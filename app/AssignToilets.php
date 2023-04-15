<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class AssignToilets extends Authenticatable
{
    use Notifiable;

    protected $table = 'assign_toilets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assign_date','vehicle_id','cleaning_type_id','zone','ward','toilet_id'
    ];


    public function saveData($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        $this->save();
    }

    public function updateData($data,$id)
    {
        $user_checker = AssignToilets::find($id);
        foreach($data as $key =>$value)
        {
            $user_checker->$key = $value;
        }
        $user_checker->update();
    }


    public static function getAssignToiletsList()
    {

        $data = DB::select('select assign_toilets.id,assign_toilets.assign_date,toilets.name,vehicles.number,cleaning_types.name as cleaning_type_name,assign_toilets.zone,assign_toilets.ward 
                            from assign_toilets 
                            LEFT JOIN toilets ON (toilets.id = assign_toilets.toilet_id)
                            LEFT JOIN vehicles ON (vehicles.id = assign_toilets.vehicle_id)
                            LEFT JOIN cleaning_types ON (cleaning_types.id = assign_toilets.cleaning_type_id)
                            where (assign_toilets.deleted_by IS NULL AND assign_toilets.image_path IS NULL AND assign_toilets.completed_by IS NULL AND (assign_toilets.assign_date = ? OR assign_toilets.assign_date = ?)) OR (assign_toilets.is_reported_not_clean is TRUE AND assign_toilets.deleted_by IS NULL)',[\Carbon\Carbon::today(),\Carbon\Carbon::tomorrow()]);

        return $data;
    }

    public static function getAssignToiletsListByVehicleId($vehicle_id,$user_type,$user_id)
    {
        $data = [];
        if($user_type == 'Driver'){
            $data = DB::select('select assign_toilets.id,toilets.name as toilet_name,toilets.number as toilet_number,toilets.address as toilet_address,toilets.latitude,toilets.longitude,vehicles.number as vehicle_number,cleaning_types.name as cleaning_type_name,assign_toilets.zone,assign_toilets.ward 
                            from assign_toilets 
                            LEFT JOIN toilets ON (toilets.id = assign_toilets.toilet_id)
                            LEFT JOIN vehicles ON (vehicles.id = assign_toilets.vehicle_id)
                            LEFT JOIN cleaning_types ON (cleaning_types.id = assign_toilets.cleaning_type_id)
                            where assign_toilets.vehicle_id = ? AND assign_toilets.assign_date = ? AND assign_toilets.deleted_by is NULL AND assign_toilets.completed_by IS NULL', [$vehicle_id,\Carbon\Carbon::today()]);

        }elseif($user_type == 'Admin'){
            $data = DB::select('select assign_toilets.id,toilets.name as toilet_name,toilets.number as toilet_number,toilets.address as toilet_address,toilets.latitude,toilets.longitude,vehicles.number as vehicle_number,cleaning_types.name as cleaning_type_name,assign_toilets.zone,assign_toilets.ward 
                            from assign_toilets 
                            LEFT JOIN toilets ON (toilets.id = assign_toilets.toilet_id)
                            LEFT JOIN vehicles ON (vehicles.id = assign_toilets.vehicle_id)
                            LEFT JOIN cleaning_types ON (cleaning_types.id = assign_toilets.cleaning_type_id)
                            where assign_toilets.assign_date = ? AND assign_toilets.deleted_by is NULL AND assign_toilets.completed_by IS NULL', [\Carbon\Carbon::today()]);

        }elseif($user_type == 'Checker'){
            $data = DB::select('select assign_toilets.id,toilets.name as toilet_name,toilets.number as toilet_number,toilets.address as toilet_address,toilets.latitude,toilets.longitude,vehicles.number as vehicle_number,cleaning_types.name as cleaning_type_name,assign_toilets.zone,assign_toilets.ward 
                            from assign_toilets 
                            LEFT JOIN toilets ON (toilets.id = assign_toilets.toilet_id)
                            LEFT JOIN vehicles ON (vehicles.id = assign_toilets.vehicle_id)
                            LEFT JOIN cleaning_types ON (cleaning_types.id = assign_toilets.cleaning_type_id)
                            JOIN user_checkers ON (user_checkers.ward = assign_toilets.ward)
                            where assign_toilets.assign_date = ? AND user_checkers.user_id = ? AND assign_toilets.deleted_by is NULL AND assign_toilets.completed_by IS NULL', [\Carbon\Carbon::today(),$user_id]);

        }

        return $data;
    }

    public static function deleteAssignToilet($id)
    {
        $obj = AssignToilets::find($id);
        $obj->deleted_at = now();
        $obj->deleted_by = Auth::user()->id;
        $obj->update();
    }

}
