<?php

namespace Tests\Setup;

use App\Model\User;
use Tests\TestCase;


class UserFactory {

    public function create()
    {
        return factory(User::class)->create();
    }
}
