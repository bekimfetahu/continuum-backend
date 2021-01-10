<?php


namespace App\Services;

use Image;
use App\Model\Client;
use App\DAO\ClientDAO;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AvatarExeption;


/**
 * Class ClientService to handle business logic for client
 * @package App\Services
 */
class ClientService
{
    protected $clientDAO = null;
    protected $client = null;
    protected $avatarService = null;

    /**
     * ClientService constructor.
     * @param Client|null $client
     * @param ClientDAO $clientDAO
     * @param AvatarService $avatarService
     */
    public function __construct(Client $client = null, ClientDAO $clientDAO, AvatarService $avatarService)
    {
        $this->clientDAO = $clientDAO;
        $this->client = $client;
        $this->avatarService = $avatarService;
    }

    /**
     * Create Client and return status message
     * Since we are uploading image, we make sure that avatar image
     * and client data is persisted successfully before commit
     * If either of this fail, DB is rolled back
     *
     * @param array $data for new Client
     * @param $avatarFile
     * @return array
     */
    public function create(array $data, $avatarFile)
    {
        $result = [];

        DB::beginTransaction();

        try {
            $avatar = $this->avatarService->createAvatar($avatarFile);
            $data = array_merge($data, ['avatar' => $avatar]);
            $this->clientDAO->create($data);
            $result['success'] = 'Client created successfully';
        } catch (AvatarExeption $exception) {
            $result['error'] = $exception->getMessage();
        } catch (\Exception $exception) {
            $result['error'] = 'Error: failed to create client' . $exception->getMessage();
        }

        isset($result['error']) ?
            DB::rollback() :
            DB::commit();

        return $result;

    }

    /**
     * @param Client $client
     * @param array $data
     * @param $avatarFile
     * @return array
     */
    public function update(Client $client, array $data, $avatarFile = null)
    {
        $result = [];

        try {
            if ($avatarFile) {
                // Delete old avatar
                $this->avatarService->deleteAvatarFile($client->avatar);
                $name = $this->avatarService->createAvatar($avatarFile);
                // set name of new avatar
                $data = array_merge($data, ['avatar' => $name]);
            }

            $this->clientDAO->update($client, $data);
            $result['success'] = 'Client updated successfully';

        } catch (AvatarExeption $exception) {
            $result['error'] = $exception->getMessage();
        } catch (\Exception $exception) {
            $result['error'] = 'Failed to update client';
        }

        return $result;
    }

    /**
     * Delete Client
     * Note: Not checking for avatar unlink exception as we want to
     * succeed DB client delete even if not possible to remove avatar file
     *
     * @param Client $client
     * @return array
     */
    public function delete(Client $client)
    {
        $result = [];

        try {
            $this->avatarService->deleteAvatarFile($client->avatar);
            $this->clientDAO->delete($client);
            $result['success'] = 'Client deleted successfully';

        } catch (\Illuminate\Database\QueryException $e) {
            $result['error'] = 'Policy violation on delete restriction';
        } catch (\Exception $exception) {
            $result['error'] = 'Failed to delete client';
        }
        return $result;
    }
}
