<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             "code" => "SP". rand(0,100) ,
            "price" => rand(1,200),
            "quantity" => rand(20,50) ,
            "thumbnail" => "https://picsum.photos/200"
        ];
    }
}
