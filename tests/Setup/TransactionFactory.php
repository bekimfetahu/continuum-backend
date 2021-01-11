<?php

namespace Tests\Setup;

use App\Model\Client;
use App\Model\Transaction;
use Tests\TestCase;


class TransactionFactory
{

    protected $client = null;

    public function create()
    {
        return factory(Transaction::class)->create([
            'client_id' => $this->client ?? factory(Transaction::class),
        ]);
    }

    public function withClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }
}
