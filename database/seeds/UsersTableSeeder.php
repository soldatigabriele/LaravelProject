<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        DB::table('users')->insert([
            'name' => 'Homestead',
            'surname' => 'Homestead',
            'email' => 'homestead@homestead',
            'other_email' => 'gabriele@22group.co.uk',
            'teamwork_id' => '000000',
            'google_id' => '0000000000',
            'profile_pic' => 'https://lh4.ggpht.com/xwuFphaGTLWnMG1mPHtto6EcBS1rUYz8VxlP5d6EjrJtx_7dQ-AG9BLDb5K7qa8X0w=w300',
            'password' => bcrypt('secret'),
            'admin' => true,
            'confirmed'=>1,
            'confirmation_code'=>uniqid(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('users')->insert([
            'name' => 'Gabriele',
            'surname' => 'Soldati',
            'email' => 'gabriele@22group.co.uk',
            'other_email' => null,
            'teamwork_id' => '171756',
            'google_id' => '106850655767651180215',
            'profile_pic' => 'https://lh3.googleusercontent.com/-RgWbHxysrGM/AAAAAAAAAAI/AAAAAAAAABU/UPArknn8gXE/photo.jpg?sz=50',
            'password' => bcrypt('106850655767651180215'),
            'admin' => false,
            'confirmed'=>0,
            'confirmation_code'=>uniqid(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
