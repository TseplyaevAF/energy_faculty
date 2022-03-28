<?php

namespace Database\Factories\News;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_date' => $this->faker->dateTimeBetween('+0 days', '+4 days'),
            'finish_date' =>$this->faker->dateTimeBetween('+0 days', '+10 days')
        ];
    }
}
