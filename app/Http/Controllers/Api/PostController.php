<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\CreatePostRequest;
    use App\Interface\PostRepositoryInterface;
    use App\Models\Post;
    use App\Traits\ResponseAPI;
    use App\Transformers\PostTransformer;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Http\JsonResponse;

    ;


    class PostController extends Controller
    {
        use ResponseAPI;

        private PostRepositoryInterface $postRepository;

        public function __construct(PostRepositoryInterface $postRepository)
        {
            $this->postRepository = $postRepository;
        }


        public function index(): JsonResponse
        {
            return $this->postRepository->getAllPosts();
        }

        public function store(CreatePostRequest $request): JsonResponse
        {
            return $this->postRepository->savePost($request);
        }

        public function show( $postId): JsonResponse
        {
            return $this->postRepository->getPost($postId);

        }


    }
