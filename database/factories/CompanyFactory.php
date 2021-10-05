<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     *       $table->string('name');
    $table->string('location');
    $table->string('description');
    $table->string('email');
    $table->string('web_site');
    $table->string('support_phone_number');
    $table->integer('category_id');
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'location' => $this->faker->locale,
            'description' => $this->faker->text(300),
            'email' => $this->faker->email(),
            'web_site' => $this->faker->url(),
            'support_phone_number' => $this->faker->phoneNumber(),
            'category_id' => $this->faker->numberBetween(1,100),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
