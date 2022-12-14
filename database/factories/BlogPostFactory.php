<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BlogPost;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(5, true),
            'created_at' => $this->faker->dateTimeBetween('-3 months'),
        ];
    }

    //creating state to provide way to override to get specific and not autogenerated attribute values.
    public function testing()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'Title',
                'content' => 'Content'
            ];
        });
    }
}
