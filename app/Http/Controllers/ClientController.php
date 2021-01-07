<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientCreateRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Resources\ClientResource;
use App\Model\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientService = null;

    public function __construct(ClientService $service)
    {
        $this->clientService = $service;
    }

    /**
     * Paginated Client listing as json client resource response.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->has('page') ? $request->page : null;

        return response()->json($this->clientService->getClients($perPage, $page));
    }


    /**
     * Store a newly created Client.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientCreateRequest $request)
    {
        return response()->json($this->clientService->create(
            $request->only(['first_name', 'last_name', 'email', 'avatar'])
        ));

    }

    /**
     * Update the Client.
     * @param \Illuminate\Http\Request $request
     * @param Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        return response()->json($this->clientService->update(
            $client, $request->only(['first_name', 'last_name', 'email', 'avatar'])), 200
        );
    }

    /**
     * Remove client
     * @param Client $client
     * @return void
     */
    public function destroy(Client $client)
    {
        return response()->json($this->clientService->delete($client));
    }

    /**
     * Get client data
     * @param Client $client
     * @return ClientResource
     */
    public function client(Client $client)
    {
        return new ClientResource($client);
    }
}
