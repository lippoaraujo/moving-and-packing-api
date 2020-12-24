<?php

namespace Modules\Moving\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Moving\Entities\Image;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'image'   => \base64_encode($this->faker->text()),
        ];
    }
}
