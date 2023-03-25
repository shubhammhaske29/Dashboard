<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class Expenses extends Authenticatable
{
    use Notifiable;

    protected $table = 'expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expense_date','vehicle_id','start_read','end_read','petrol_amount','diesel_amount'
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
        $user_checker = Expenses::find($id);
        foreach($data as $key =>$value)
        {
            $user_checker->$key = $value;
        }
        $user_checker->update();
    }


    public static function getTodayExpense($vehicle_id)
    {
        $expense = Expenses::where('vehicle_id','=',$vehicle_id)
            ->where('expense_date', '=', date("Y-m-d"))
            ->first();

        return $expense;
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

    public static function deleteAssignToilet($id)
    {
        Expenses::where('id',$id)->delete();
    }

}
