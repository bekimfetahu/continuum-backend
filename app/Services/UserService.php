<?php


namespace App\Services;

use App\DAO\UserDAO;
use Image;
use App\Model\Client;
use App\DAO\ClientDAO;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AvatarExeption;

/**
 * Class UserService to handle business logic for user
 * @package App\Services
 */
class UserService
{
    protected $userDAO = null;

    public function __construct(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function findByEmail($email)
    {
        return $this->userDAO->findByEmail($email);
    }
}
