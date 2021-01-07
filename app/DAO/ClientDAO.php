<?php


namespace App\DAO;

use App\Model\Client;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Class ClientDAO
 * Data access object wrapper for Client
 * @package App\DAO
 */
class ClientDAO
{
    public function all()
    {
        return Client::all();
    }

    public function find(int $id)
    {
        return Client::find($id);
    }

    public function create(array $data)
    {
        $client = new Client();
        $client->fill($data);
        $client->save();

        return $client->refresh();
    }

    public function update(Client $client, array $attributes)
    {
        return $client->update($attributes);
    }

}
