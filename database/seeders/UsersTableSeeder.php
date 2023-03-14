<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("users")->insert([
            "is_active" => 1,
            //            "role_id" => 1,
            "name" => "Lucas",
            "email" => "lucas.delaere@hotmail.com",
            "photo_id" => 1,
            "password" => bcrypt(12345678),
            "email_verified_at" => Carbon::now()->format("Y-m-d H:i:s"), //so we can log in
            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
        ]);
        DB::table("users")->insert([
            "is_active" => 1,
            //            "role_id" => 1,
            "name" => "Tom",
            "email" => "Tom@hotmail.com",
            "photo_id" => 1,
            "password" => bcrypt(12345678),
            "email_verified_at" => Carbon::now()->format("Y-m-d H:i:s"),
            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
        ]);
        User::factory()
            ->count(50)
            ->create();
    }
}
