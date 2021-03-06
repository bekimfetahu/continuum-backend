<?php


namespace App\DAO;

use App\Model\Client;

/**
 * Class ClientDAO
 * Data access object wrapper for Client
 * @package App\DAO
 */
class ClientDAO
{
    /**
     * Find client by id
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Client::find($id);
    }

    /**
     * Creates a Client
     * @param array $data
     * @return Client
     */
    public function create(array $data)
    {
        $client = new Client();
        $client->fill($data);
        $client->save();

        return $client->refresh();
    }

    /**
     * Updates client with new attributes
     * @param Client $client
     * @param array $attributes
     * @return bool
     */
    public function update(Client $client, array $attributes)
    {
        return $client->update($attributes);
    }

    /**
     * Removes Client.
     * Note: integrity constraint will not allow to delete if there are client transactions
     * @param Client $client
     * @return bool|null
     */
    public function delete(Client $client)
    {
        return $client->forceDelete();
    }
}
