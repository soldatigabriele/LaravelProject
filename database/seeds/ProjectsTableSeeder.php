<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            'project_id' => '228701',
            'project_name' => '22 Time Log - Gabriele',
            'fk_user' => '2',
        ]);
        DB::table('projects')->insert([
            'project_id' => '228759',
            'project_name' => '13.33 Hour Retainer - £800',
            'fk_user' => '2',
        ]);
    }
}
