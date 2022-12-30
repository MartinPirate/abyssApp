<?php

    namespace Database\Factories;

    use App\Enums\FileType;
    use App\Models\Post;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Facades\Storage;
    use JetBrains\PhpStorm\ArrayShape;

    /**
     * @extends Factory<Post>
     */
    class PostFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        #[ArrayShape(['name' => "string", 'description' => "string", 'type' => "mixed", 'file' => 'string'])]
        public function definition(): array
        {

            return [
                'name' => $this->faker->name,
                'description' => $this->faker->text,
                'type' => $this->faker->randomElement(FileType::cases()),
                'file' => 'test.png'
            ];
        }
    }
