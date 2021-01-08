<?php


namespace App\Services;


use App\DAO\TransactionDAO;
use App\Model\Client;


/**
 * Class TransactionService to handle business logic for client transactions
 * @package App\Services
 */
class TransactionService
{
    protected $transactionDAO = null;
    protected $client = null;

    public function __construct(Client $client = null)
    {
        $this->transactionDAO = new transactionDAO();
        $this->client = $client;
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
            $result['error'] = 'Failed to create transaction '.$exception->getMessage();
        }

        return $result;
    }

    public function update(Client $client, array $data)
    {

        $result = [];

        try {

            $cl = $this->transactionDAO->update($client, $data);
            $result['success'] = 'Client updated successfully';

        } catch (\Exception $exception) {
            $result['error'] = 'Error: failed to update client';
        }

        return $result;
    }

    public function delete(Client $client)
    {

        $result = [];

        try {

            $cl = $this->transactionDAO->delete($client);
            $result['success'] = 'Client deleted successfully';

        } catch (\Illuminate\Database\QueryException $e) {
            $result['error'] = 'Policy violation on delete restriction';
        } catch (\Exception $exception) {
            $result['error'] = 'Failed to delete client';
        }

        return $result;
    }

    /**
     * @param $perPage
     * @return mixed
     */
    public function getClients($perPage)
    {
        return $this->transactionDAO->paginate($perPage);

    }

}
