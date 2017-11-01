<?php

use Illuminate\Database\Seeder;


class TagTableSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        DB::table('tags')->insert([
            'id' => 0,
            'name' => 'General Task',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('tags')->insert([
            'id' => 1,
            'name' => 'Upload file',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('tags')->insert([
            'id' => 2,
            'name' => 'Setup Gocardless',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('tags')->insert([
            'id' => 3,
            'name' => 'Payment',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('tags')->insert([
            'id' => 4,
            'name' => 'Development Sign-Off',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('tags')->insert([
            'id' => 5,
            'name' => 'Design Sign-Off',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}



