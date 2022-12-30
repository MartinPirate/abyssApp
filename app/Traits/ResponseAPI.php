<?php
    namespace App\Traits;

    use Illuminate\Http\JsonResponse;

    trait ResponseAPI
    {
        /**
         * Core of response
         *
         * @param string $message
         * @param null $data
         * @param integer $statusCode
         * @param boolean $isSuccess
         * @return JsonResponse
         */
        public function coreResponse(string $message, $data = null, int $statusCode, bool $isSuccess = true): JsonResponse
        {
            // Check the params
            if(!$message) return response()->json(['message' => 'Message is required'], 500);

            // Send the response
            if($isSuccess) {
                return response()->json([
                    'message' => $message,
                    'error' => false,
                    'code' => $statusCode,
                    'results' => $data
                ], $statusCode);
            } else {
                return response()->json([
                    'message' => $message,
                    'error' => true,
                    'code' => $statusCode,
                ], $statusCode);
            }
        }

        /**
         * Send any success response
         *
         * @param string $message
         * @param object|array $data
         * @param integer $statusCode
         * @return JsonResponse
         */
        public function success(string $message, object|array $data, int $statusCode = 200): JsonResponse
        {
            return $this->coreResponse($message, $data, $statusCode);
        }

        /**
         * Send any error response
         *
         * @param string $message
         * @param integer $statusCode
         * @return JsonResponse
         */
        public function error(string $message, int $statusCode = 500): JsonResponse
        {
            return $this->coreResponse($message, null, $statusCode, false);
        }
    }
