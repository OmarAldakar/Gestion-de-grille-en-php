<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Admin;
use App\User;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id
    ];
});
