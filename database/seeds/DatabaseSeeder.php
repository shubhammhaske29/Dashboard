<?php
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $timestamp = \Carbon\Carbon::now();

        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                'name'        => 'admin',
                'email'       => 'admin@gmail.com',
                'password'    => bcrypt('admin123'),
                'role_id'     => config('common.user_roles.Admin'),
                'token'       => bcrypt('admin@gmail.com'.$timestamp),
                'created_at'  => \Carbon\Carbon::now(),
                'updated_at'  => \Carbon\Carbon::now()
            ]
        ]);
    }
}
