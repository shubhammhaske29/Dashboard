<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role_id', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function saveData($data)
    {
        try {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
            $this->save();
        } catch (\Exception $exception) {
            throw new \Exception($data->name ." : Username already exist try different username");
        }
    }

    public function updateData($data,$id)
    {
        $user = User::find($id);
        foreach($data as $key =>$value)
        {
            $user->$key = $value;
        }
        $user->update();
    }

    //Delete User
    public static function deleteUser($id)
    {
        $user = User::find($id);
        $user->deleted_at = now();
        $user->deleted_by = Auth::user()->id;
        $user->update();
    }

    public static function getUserList()
    {
        $users = User::select('users.id', 'users.name', 'users.role_id')
            ->where('users.id', '!=', 1)
            ->whereNull('users.deleted_by')
            ->orderBy('users.id', 'DESC')
            ->get();

        return $users;
    }

}
