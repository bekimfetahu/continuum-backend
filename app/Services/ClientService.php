<?php


namespace App\Services;


use App\DAO\ClientDAO;
use App\Http\Resources\ClientResource;
use App\Model\Client;


/**
 * Class ClientService to handle business logic for client
 * @package App\Services
 */
class ClientService
{
    protected $clientDAO = null;
    protected $client = null;

    public function __construct(Client $client = null)
    {
        $this->clientDAO = new ClientDAO();
        $this->client = $client;
    }

    /**
     * Create Client and return status message
     * @param $data
     * @return array
     */
    public function create(array $data)
    {
        $result = [];

        try {

            $this->clientDAO->create($data);
            $result['success'] = 'Client created successfully';

        } catch (\Exception $exception) {
            $result['error'] = 'Error: failed to create client' . $exception->getMessage();
        }

        return $result;
    }

    public function update(Client $client, array $data)
    {

        $result = [];

        try {

            $cl = $this->clientDAO->update($client, $data);
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

            $cl = $this->clientDAO->delete($client);
            $result['success'] = 'Client deleted successfully';

        } catch (\Exception $exception) {
            $result['error'] = 'Error: failed to delete client';
        }

        return $result;
    }

    /**
     * @param $perPage
     * @return mixed
     */
    public function getClients($perPage)
    {
        return $this->clientDAO->paginate($perPage);

    }

}
