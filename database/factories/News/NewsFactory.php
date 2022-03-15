<?php

namespace Database\Factories\News;

use App\Models\Chair;
use App\Models\News\Category;
use App\Models\News\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(rand(10,30)),
            'content' => $this->faker->text,
            'images' => json_encode([$this->faker->imageUrl]),
            'category_id' => Category::get()->random()->id,
            'chair_id' => Chair::get()->random()->id,
        ];
    }
}
