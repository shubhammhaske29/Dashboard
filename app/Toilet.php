<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Toilet extends Authenticatable
{
    use Notifiable;

    protected $table = 'toilets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zone','ward','name','number','address','latitude','longitude'
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
        $toilet = Toilet::find($id);
        foreach($data as $key =>$value)
        {
            $toilet->$key = $value;
        }
        $toilet->update();
    }


    public static function getToiletList()
    {
        $toilets = Toilet::select('toilets.id', 'toilets.zone','toilets.ward','toilets.name','toilets.number','toilets.address','toilets.latitude','toilets.longitude')
            ->whereNull('toilets.deleted_by')
            ->orderBy('toilets.id', 'DESC')
            ->get();

        return $toilets;
    }

    public static function getToiletLists($date)
    {

        $toilet_ids = AssignToilets::select('assign_toilets.toilet_id')
            ->where('assign_toilets.assign_date', '=', $date)
            ->whereNull('assign_toilets.deleted_by')
            ->get()->toArray();

        $toilets = Toilet::select('toilets.id', 'toilets.name','toilets.ward')
            ->whereNotIn('toilets.id', $toilet_ids)
            ->whereNull('toilets.deleted_by')
            ->orderBy('toilets.id', 'DESC')
            ->get();

        return $toilets;
    }

    public static function deleteToilet($id)
    {
        $toilet = Toilet::find($id);
        $toilet->deleted_at = now();
        $toilet->deleted_by = Auth::user()->id;
        $toilet->update();
    }

}
