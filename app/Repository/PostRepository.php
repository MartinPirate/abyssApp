<?php

    namespace App\Repository;

    use App\Http\Requests\CreatePostRequest;
    use App\Interface\PostRepositoryInterface;
    use App\Models\Post;
    use App\Traits\ResponseAPI;
    use App\Transformers\PostTransformer;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Storage;

    class PostRepository implements PostRepositoryInterface
    {

        use ResponseAPI;

        public function getAllPosts(): JsonResponse
        {
            $posts = Post::latest()
                ->paginate(10);

            $response = fractal()
                ->collection($posts, new PostTransformer());

            try {
                return $this->success("List of all Posts", $response);
            } catch (Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        }


        public function savePost(CreatePostRequest $request): JsonResponse
        {
            if ($request->hasFile('file')) {
                $file_name = $this->fileHandler($request->file('file'));
            }

            $post = new Post();
            $post->name = $request->get('name');
            $post->type = $request->get('type');
            $post->description = $request->get('description');
            $post->file = $file_name;
            $post->save();


            $response = fractal()
                ->item($post, new PostTransformer());

            try {
                return $this->success("Post Created Successfully", $response);
            } catch (Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        }

        public function getPost($postId): JsonResponse
        {

            $post = Post::whereId($postId)->first();

            if (empty($post)) {
                return $this->error("Post Not Found", 404);
            }

            try {
                if ($post->file) {
                    $temp_url = Storage::disk('local')->temporaryUrl('files/' . $post->file, now()->addMinutes(10));

                    $url = Storage::disk('local')->temporaryUrl(
                        'files/' . $post->file,
                        Carbon::now()->addMinutes(10)
                    );
                }

                $data = [
                    'name' => $post->name,
                    'description' => $post->description,
                    'type' => $post->type,
                    'file' => $url
                ];

                return $this->success("Post Details", $data);

            } catch (Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        }

        public function fileHandler($file): ?string
        {
            $file_extension = $file->extension();
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = 'files/' . $file_name;
            Storage::disk('local')->put($file_path, file_get_contents($file));
            return $file_name;
        }
    }
