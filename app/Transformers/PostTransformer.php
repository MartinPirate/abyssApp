<?php

    namespace App\Transformers;

    use App\Models\Post;
    use League\Fractal\Resource\Item;
    use League\Fractal\TransformerAbstract;

    class PostTransformer extends TransformerAbstract
    {


        /**
         * List of resources to automatically include
         *
         * @var array
         */
        protected array $defaultIncludes = [
            //
        ];

        /**
         * List of resources possible to include
         *
         * @var array
         */
        protected array $availableIncludes = [
        ];

        /**
         * A Fractal transformer.
         *
         * @param Post $post
         * @return array
         */
        public function transform(Post $post): array
        {

            return [
                'name' => $post->name,
                'description' => $post->description,
                'type' => $post->type,
            ];
        }

    }
