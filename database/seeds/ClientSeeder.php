<?php

use Illuminate\Database\Seeder;
use App\Model\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Client::all()->count() > 50) { // ignore re attempts
            return;
        }
        factory(Client::class, 50)->create();
    }
}
