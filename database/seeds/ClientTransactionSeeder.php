<?php

use Illuminate\Database\Seeder;
use App\Model\Client;
use App\Model\Transaction;

class ClientTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * :
     */
    public function run()
    {

        $clients = factory(Client::class, 20)->create();

        $skip = 0; // Don't insert transaction for first 10 clients to allow for delete constraint onDelete restrict

        foreach ($clients as $client){
            if(++$skip > 10){
                factory(Transaction::class, 25)
                    ->create(['client_id' => $client->id]);
            }
        }


    }
}
