<?php

namespace Database\Factories\Student;

use App\Models\Group\Group;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $students = Student::all();
        return [
            'student_id_number' => $this->faker->unique()->numberBetween(1, $students->count()),
            'group_id' => Group::get()->random()->id,
        ];
    }
}
