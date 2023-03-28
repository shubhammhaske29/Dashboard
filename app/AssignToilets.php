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
        $data = DB::table('assign_toilets')
            ->leftJoin('toilets', 'toilets.id', '=', 'assign_toilets.toilet_id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'assign_toilets.vehicle_id')
            ->leftJoin('cleaning_types', 'cleaning_types.id', '=', 'assign_toilets.cleaning_type_id')
            ->select('assign_toilets.id','assign_toilets.assign_date', 'toilets.name','vehicles.number','cleaning_types.name as cleaning_type_name','assign_toilets.zone','assign_toilets.ward')
            ->whereNull('assign_toilets.deleted_by')
            ->whereNull('assign_toilets.image_path')
            ->WhereNull('assign_toilets.completed_by')
            ->orWhere('assign_toilets.is_reported_not_clean',true)
            ->whereNull('assign_toilets.deleted_by')
            ->get();

        return $data;
    }

    public static function getAssignToiletsListByVehicleId($vehicle_id)
    {
        $data = DB::table('assign_toilets')
            ->leftJoin('toilets', 'toilets.id', '=', 'assign_toilets.toilet_id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'assign_toilets.vehicle_id')
            ->leftJoin('cleaning_types', 'cleaning_types.id', '=', 'assign_toilets.cleaning_type_id')
            ->select('assign_toilets.id','toilets.name as toilet_name', 'toilets.number as toilet_number','toilets.address as toilet_address','toilets.latitude','toilets.longitude','vehicles.number as vehicle_number','cleaning_types.name as cleaning_type_name','assign_toilets.zone','assign_toilets.ward')
            ->where('assign_toilets.vehicle_id', '=', $vehicle_id)
            ->whereNull('assign_toilets.deleted_by')
            ->WhereNull('assign_toilets.completed_by')
            ->get();

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
