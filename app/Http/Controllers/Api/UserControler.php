<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserControler extends Controller
{
    /**
     * @lrd:start
     * # Give Users data 
     * Use *inclidePosts* accept data with post wich wrote user
     * @lrd:end
     */
    public function index(UserIndexRequest $request)
    {
        $inclodePosts = $request->query('includePosts');

        $users = User::all();

        if (filter_var($inclodePosts, FILTER_VALIDATE_BOOLEAN)) {
            $users = User::with('posts')->paginate();
        }

        return new UserCollection($users);
    }

    public function store(StoreUserRequest $request)
    {
        return new UserResource(User::create($request->all()));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function destroy(Request $request, User $user)
    {
        $rUser = $request->user();

        if ($rUser !== null && $rUser->tokenCan('server:delete')) {

            if ($user->delete($user)) {
                return ['id' => $user->id];
            }

            return response(['delete' => 'Not deleted']);
        }

        throw ValidationException::withMessages([
            'token' => 'Permission denied'
        ]);
    }
}
