<?php

use Illuminate\Database\Seeder;
use App\Model\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::all()->count() > 0) { // to make sure no more than one user in the re attempt
            return;
        }
        factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);
    }
}
