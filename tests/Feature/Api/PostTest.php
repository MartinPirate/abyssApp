<?php

    namespace Tests\Feature\Api;

    use App\Enums\FileType;
    use App\Models\Post;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Faker\Factory;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Tests\TestCase;

    class PostTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;


        /** @test */
         public function can_get_all_posts()
         {
             // Create some test records in the database
           $posts =  Post::factory()->count(2)->create();

             // Send a GET request to the API endpoint
             $response = $this->get('/api/posts');

             // Assert that the response has the correct status code
             $response->assertStatus(200);

             // Assert that the response has the correct content type
             $response->assertHeader('Content-Type', 'application/json');


             //assert json structure
             $response->assertJson([

                 "message" => "List of all Posts",
                 "error" => false,
                 "code" => 200,
                 "results" => [
                     "data" => []
                 ]]);

         }


        /** @test */
        public function can_store_a_valid_post()
        {

            $file = UploadedFile::fake()->create('test.png');

            $newPost = [
                'name' => $this->faker->name,
                'description' => $this->faker->sentence,
                'type' => 1,
                'file' => $file
            ];

            $response = $this->postJson(
                route('posts.save'),
                $newPost
            );
            //assert created
            $response->assertStatus(200);

            //assert response
            $response->assertJson([

                "message" => "Post Created Successfully",
                "error" => false,
                "code" => 200,
                "results" => [
                    "data" => [
                        'name' => $newPost['name'],
                        'type' => $newPost['type'],
                        'description' => $newPost['description']
                    ]
                ]]);


            //assert database increment
            $this->assertDatabaseHas('posts', ['name' => $newPost['name']]);

        }

        /** @test
         * @throws \Throwable
         */
        public function name_field_is_required()
        {
            $file = UploadedFile::fake()->create('test.png');

            $newPost = [
                'name' => '',
                'description' => $this->faker->sentence,
                'type' => 1,
                'file' => $file
            ];

             $response = $this->post(
                 route('posts.save'),
                $newPost
             );

             $response->assertStatus(422);

             $response->assertHeader('Content-Type', 'application/json');

             $data = $response->decodeResponseJson();
             $this->assertArrayHasKey('message', $data);
             $this->assertArrayHasKey('error', $data);

        }

        /** @test */
        public function description_field_is_required()
        {
            $file = UploadedFile::fake()->create('test.png');

            $newPost = [
                'name' =>  $this->faker->name,
                'description' => '',
                'type' => 1,
                'file' => $file
            ];

            $response = $this->post(
                route('posts.save'),
                $newPost
            );

            $response->assertStatus(422);

            $response->assertHeader('Content-Type', 'application/json');

            $data = $response->decodeResponseJson();
            $this->assertArrayHasKey('message', $data);
            $this->assertArrayHasKey('error', $data);
        }

        /** @test */
        public function type_field_is_required()
        {
            $file = UploadedFile::fake()->create('test.png');

            $newPost = [
                'name' =>  $this->faker->name,
                'description' =>  $this->faker->sentence,
                'type' => '',
                'file' => $file
            ];

            $response = $this->post(
                route('posts.save'),
                $newPost
            );

            $response->assertStatus(422);

            $response->assertHeader('Content-Type', 'application/json');

            $data = $response->decodeResponseJson();
            $this->assertArrayHasKey('message', $data);
            $this->assertArrayHasKey('error', $data);
        }

        /** @test */
        public function type_can_not_be_invalid()
        {
            $file = UploadedFile::fake()->create('test.png');

            $newPost = [
                'name' =>  $this->faker->name,
                'description' =>  $this->faker->sentence,
                'type' => 6,
                'file' => $file
            ];

            $response = $this->post(
                route('posts.save'),
                $newPost
            );

            $response->assertStatus(422);

            $response->assertHeader('Content-Type', 'application/json');

            $data = $response->decodeResponseJson();
            $this->assertArrayHasKey('message', $data);
            $this->assertArrayHasKey('error', $data);
        }

        /** @test */
        public function files_larger_than_5bms_can_not_be_uploaded()
        {
            $file = UploadedFile::fake()->create('test.png', 6000);

            $newPost = [
                'name' =>  $this->faker->name,
                'description' =>  $this->faker->sentence,
                'type' => 6,
                'file' => $file
            ];

            $response = $this->post(
                route('posts.save'),
                $newPost
            );

            $response->assertStatus(422);

            $response->assertHeader('Content-Type', 'application/json');

            $data = $response->decodeResponseJson();
            $this->assertArrayHasKey('message', $data);
            $this->assertArrayHasKey('error', $data);
        }

        /** @test */
        public function invalid_files_can_not_be_uploaded()
        {
            $file = UploadedFile::fake()->create('test.pdf');

            $newPost = [
                'name' =>  $this->faker->name,
                'description' =>  $this->faker->sentence,
                'type' => 6,
                'file' => $file
            ];

            $response = $this->post(
                route('posts.save'),
                $newPost
            );

            $response->assertStatus(422);

            $response->assertHeader('Content-Type', 'application/json');

            $data = $response->decodeResponseJson();
            $this->assertArrayHasKey('message', $data);
            $this->assertArrayHasKey('error', $data);
        }

    }
