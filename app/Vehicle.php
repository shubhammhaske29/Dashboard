<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class Vehicle extends Authenticatable
{
    use Notifiable;

    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
    ];


    public function saveData($data)
    {
        try {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
            $this->save();
        } catch (\Exception $exception) {
            throw new \Exception($data->number ." : Vehicle Number already exist try different Number");
        }
    }

    public function updateData($data,$id)
    {
        $vehicle = Vehicle::find($id);
        foreach($data as $key =>$value)
        {
            $vehicle->$key = $value;
        }
        $vehicle->update();
    }

    //Delete User
    public static function deleteVehicle($id)
    {
        $vehicle = Vehicle::find($id);
/*        $vehicle->deleted_at = now();
        $vehicle->deleted_by = Auth::user()->id;*/
        $vehicle->delete();
    }

    public static function getVehicleList()
    {
        $vehicles = Vehicle::select('vehicles.id', 'vehicles.number')
            ->whereNull('vehicles.deleted_by')
            ->orderBy('vehicles.id', 'DESC')
            ->get();

        return $vehicles;
    }

    public static function getNotAssignVehicleList()
    {

        $vehicles = DB::select('select distinct(vehicles.id),vehicles.number from vehicles 
                            LEFT JOIN users ON (users.vehicle_id = vehicles.id)
                            where (users.assign_vehicle_date != ? OR users.assign_vehicle_date IS NULL) AND vehicles.deleted_by IS NULL',[\Carbon\Carbon::today()]);


        return $vehicles;
    }

}
