<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(TagTableSeeder::class);
//        add manually these projects: 228701 , 228759
//         $this->call(ProjectsTableSeeder::class);
//         $this->call(FoldersTableSeeder::class);
    }
}
