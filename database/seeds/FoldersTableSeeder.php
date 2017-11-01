<?php

use Illuminate\Database\Seeder;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('folders')->insert([
            'folder_id' => '0B676pwgupUJbTHl0dDJtaHJsbnc',
            'folder_name' => 'default',
            'fk_project' => '228701',
            'fk_user' => '2',
        ]);
        DB::table('folders')->insert([
            'folder_id' => '0B676pwgupUJbZ0ZHcjZoMTFkVWM',
            'folder_name' => 'default',
            'fk_project' => '228759',
            'fk_user' => '2',
        ]);
    }
}
