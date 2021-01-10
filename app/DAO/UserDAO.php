<?php


namespace App\DAO;

use App\Model\User;

/**
 * Class UserDAO
 * Data access object wrapper for User
 * @package App\DAO
 */
class UserDAO
{
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
}
