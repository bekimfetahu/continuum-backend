<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\User;
use App\Model\Transaction;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomFloat(1,1,150),
        'client_id'=>null,
    ];
});
