<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserControler::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
});

/**
 * @lrd:start
 * # Login with created user for accept token
 * @lrd:end
 */
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:5|max:15',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect'],
        ]);
    }

    if ($user->email === 'admin@admin.com' && $user->id === 1) {
        $token['admin'] = $user->createToken('admin-token', ['server:update', 'server:create', 'server:delete'])->plainTextToken;
        $token['update'] = $user->createToken('update-token', ['server:update', 'server:create'])->plainTextToken;
    };

    $token['basic'] = $user->createToken('basic-token')->plainTextToken;

    return $token;
});
