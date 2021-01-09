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

    private $imageHeight = 100;
    private $imageWidth = 100;
    private $avatarDirectory = null;

    public function __construct(Client $client = null)
    {
        $this->clientDAO = new ClientDAO();
        $this->client = $client;
        $this->avatarDirectory = storage_path('/app/public/avatars');
    }

    /**
     * * Create Client and return status message
     * Since we are uploading image, we make sure that image and data is persisted successfully before commit
     * @param array $data
     * @param $avatarFile
     * @return array
     */
    public function create(array $data, $avatarFile)
    {
        $result = [];

        DB::beginTransaction();

        try {
            $avatar = $this->createAvatar($avatarFile);
            $data = array_merge($data, ['avatar' => $avatar]);
            $this->clientDAO->create($data);
            $result['success'] = 'Client created successfully';
            DB::commit();
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
            if($avatarFile){
                // Delete old avatar
                $this->deleteAvatarFile($client->avatar);
                $name = $this->createAvatar($avatarFile);
                $data = array_merge($data,['avatar',$name]);
            }

            $this->clientDAO->update($client, $data);
            $result['success'] = 'Client updated successfully';

        } catch (\Exception $exception) {
            $result['error'] = 'Error: failed to update client';
        }

        return $result;
    }

    /**
     * @param Client $client
     * @return array
     */
    public function delete(Client $client)
    {
        $result = [];

        try {
            $cl = $this->clientDAO->delete($client);
            $result['success'] = 'Client deleted successfully';

        } catch (\Illuminate\Database\QueryException $e) {
            $result['error'] = 'Policy violation on delete restriction';
        } catch (\Exception $exception) {
            $result['error'] = 'Failed to delete client';
        }

        return $result;
    }

    /**
     * Upload avatar image
     * Avatar must be at minimum 100x100.
     * Larger images are acceptable but with equally side dimensions to maintain aspect ratio
     * @param $file image
     * @return string - name of the file
     * @throws AvatarExeption|Exception
     */
    public function createAvatar($file)
    {
        try {
            $name = uniqid() . '.' . $file->extension();

            if (!is_dir($this->avatarDirectory)) {
                mkdir($this->avatarDirectory, '0775', true);
            }
            $img = Image::make($file->path());

            // Resize image in proportionally

            $img->resize($this->imageWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Check if image have valid dimensions 100 x 100 after resize
            if (!$this->validDimensions($img)) {
                throw new AvatarExeption('Avatar should have equal width and height with a minim of 100 x 100 pixels');
            }

            $img->save($this->avatarDirectory . '/' . $name);

        } catch (AvatarExeption $exception) { // We know that dimensions are incorrect
            throw new AvatarExeption($exception->getMessage());
        } catch (\Exception $exception) {   // Some other problems with file processing
            throw new AvatarExeption('Error uploading avatar');
        }
        return $name;
    }

    /**
     * @param $img
     * @return bool
     */
    private function validDimensions($img)
    {
        return ($img->height() == $this->imageHeight && $img->width() == $this->imageWidth);
    }

    /**
     * Delete avatar file
     * We are not throwing any exception as file cold have been deleted manually
     * @param $name
     */
    private function deleteAvatarFile($name)
    {
        if (is_file($this->avatarDirectory . '/' . $name)) {
            unlink($this->avatarDirectory . '/' . $name);
        }
    }
}
