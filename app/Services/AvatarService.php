<?php


namespace App\Services;

use Image;
use App\Exceptions\AvatarExeption;

/**
 * Class AvatarService to handle logic for create client avatars
 * @package App\Services
 */
class AvatarService
{
    private $imageHeight = 100;
    private $imageWidth = 100;
    private $avatarDirectory = null;

    public function __construct(Client $client = null)
    {
        $this->avatarDirectory = storage_path('/app/public/avatars');
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
    public function deleteAvatarFile($name)
    {
        if (is_file($this->avatarDirectory . '/' . $name)) {
            unlink($this->avatarDirectory . '/' . $name);
        }
    }
}
