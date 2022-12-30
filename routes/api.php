<?php

    use App\Http\Controllers\Api\PostController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts');
        Route::post('store', [PostController::class, 'store'])->name('posts.save');
        Route::get('/{postId}', [PostController::class, 'show'])->name('posts.show');

    });

    Route::get('local/temp/{path}', function (string $path) {
        //return "git gud";

       return Storage::disk('local')->download($path);

    })->name('local.temp');
