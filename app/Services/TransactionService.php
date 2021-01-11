<?php


namespace App\Services;


use App\DAO\TransactionDAO;
use App\Model\Transaction;
use App\Model\Client;


/**
 * Class TransactionService to handle business logic for client transactions
 * @package App\Services
 */
class TransactionService
{
    protected $transactionDAO = null;
    protected $client = null;

    /**
     * TransactionService constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client;
        $this->transactionDAO = new TransactionDAO;
    }

    /**
     * Create Client transaction and return status message
     * @param $data
     * @return array
     */
    public function create(array $data)
    {
        $result = [];

        try {

            $this->transactionDAO->create($this->client, $data);
            $result['success'] = 'Transaction created successfully';

        } catch (\Exception $exception) {
            $result['error'] = 'Failed to create transaction';
        }

        return $result;
    }

    /**
     * Delete client transaction
     * @param Transaction $transaction
     * @return array
     */
    public function delete(Transaction $transaction)
    {
        $result = [];
        try {
            $this->transactionDAO->delete($transaction);
            $result['success'] = 'Transaction deleted successfully';

        } catch (\Exception $exception) {
            $result['error'] = 'Failed to delete transaction';
        }
        return $result;
    }
}
