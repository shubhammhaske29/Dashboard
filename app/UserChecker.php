<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class UserChecker extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_checkers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','zone','ward'
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
        $user_checker = UserChecker::find($id);
        foreach($data as $key =>$value)
        {
            $user_checker->$key = $value;
        }
        $user_checker->update();
    }


    public static function getUserCheckerList()
    {
        $user_checkers = DB::table('user_checkers')
            ->leftJoin('users', 'users.id', '=', 'user_checkers.user_id')
            ->select('user_checkers.id','users.name', 'user_checkers.user_id','user_checkers.zone','user_checkers.ward')
            ->get();

        return $user_checkers;
    }

    public static function getUsersForCheckerAssignment()
    {
        $checker_id = config('common.user_roles.Checker');
        $users = DB::table('users')
            ->leftJoin('user_checkers', 'users.id', '=', 'user_checkers.user_id')
            ->select('users.id', 'users.name')
            ->where('users.role_id', '=', $checker_id)
            ->whereNull('user_checkers.id')
            ->orderBy('users.id', 'DESC')
            ->get();

        return $users;
    }

    public static function deleteUserChecker($id)
    {
        UserChecker::where('id',$id)->delete();
    }

}
