<?php


namespace App\DAO;

use App\Model\Client;
use App\Model\Transaction;

/**
 * Class TransactionDAO
 * Data access object wrapper for Transaction
 * @package App\DAO
 */
class TransactionDAO
{
    public function all()
    {
        return Transaction::all();
    }

    public function find(int $id)
    {
        return Transaction::find($id);
    }

    public function create(Client $client, array $data)
    {
        return $client->transactions()->create($data);
    }

    /**
     * Removes Transaction.
     * Note: In reality we will not delete transaction but cancel it!
     * This is for demo purpose of CRUD
     * @param Transaction $transaction
     * @return bool|null
     */
    public function delete(Transaction $transaction)
    {
        return $transaction->forceDelete();
    }

}
