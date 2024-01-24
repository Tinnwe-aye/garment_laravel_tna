<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("category")->truncate();
        DB::table("category")->insert(array(
            array(
                "name"      =>  "Woman Wear",
                "type"          =>  "F",
                "created_emp"   =>  "1001",
                "updated_emp"   =>  "1001",
                "created_at"    =>  Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at"    =>  Carbon::now()->format("Y-m-d H:i:s")
            ),
            array(
                "name"      =>  "Baby Wear",
                "type"          =>  "Baby",
                "created_emp"   =>  "1001",
                "updated_emp"   =>  "1001",
                "created_at"    =>  Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at"    =>  Carbon::now()->format("Y-m-d H:i:s")
            ),
            array(
                "name"      =>  "Man Wear",
                "type"          =>  "M",
                "created_emp"   =>  "1001",
                "updated_emp"   =>  "1001",
                "created_at"    =>  Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at"    =>  Carbon::now()->format("Y-m-d H:i:s")
            ),
        ));
    }
}
