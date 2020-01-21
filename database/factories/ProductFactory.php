<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\Constant;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'type' => collect([
            Constant::PRODUCT_TYPE_0,
            Constant::PRODUCT_TYPE_1,
            Constant::PRODUCT_TYPE_2
        ])->shuffle()->first(),
        'price' => $faker->randomFloat(2, 1, 12),
    ];
});
