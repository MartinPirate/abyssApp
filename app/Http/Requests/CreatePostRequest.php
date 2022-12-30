<?php

    namespace App\Http\Requests;

    use App\Enums\FileType;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Validation\Rules\Enum;
    use Symfony\Component\HttpFoundation\JsonResponse;
    class CreatePostRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize(): bool
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, mixed>
         */
        public function rules(): array
        {
            return [
                'name' => ['required', 'max:50', 'string'],
                'description' => ['required', 'max:250', 'string'],
                'type' => [new Enum(FileType::class)],
                'file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5000'],
            ];
        }


        public function failedValidation(Validator $validator)
        {
            $messageBag = collect($validator->errors()->messages());
            $message = implode('|', $messageBag->flatten()->toArray());
            throw new HttpResponseException(response()->json(['error' => true, 'message' => $message], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }

    }
