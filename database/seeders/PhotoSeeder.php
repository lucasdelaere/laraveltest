<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        DB::table("photos")->insert([
        //            "file" => time() . "batman.png",
        //            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
        //            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
        //        ]);
        Storage::disk("public")->deleteDirectory("posts");
        Photo::factory()
            ->count(10)
            ->create();
    }
}
