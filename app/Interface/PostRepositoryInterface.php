<?php

    namespace App\Interface;

    use App\Http\Requests\CreatePostRequest;
    use App\Models\Post;
    use Illuminate\Http\JsonResponse;

    interface PostRepositoryInterface
    {
        public function getAllPosts(): JsonResponse;

        public function savePost(CreatePostRequest $request): JsonResponse;

        public function getPost(int $postId): JsonResponse;

    }
