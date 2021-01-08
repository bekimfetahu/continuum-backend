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
        factory(Client::class, 20)->create()
            ->each(function ($client) {
                factory(Transaction::class, 25)->create(
                    [
                        'client_id' => $client->id
                    ]
                );
            });

    }
}
