<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @LRDparam includeComments boolean
     * // override the default response codes
     * @LRDresponses 200|422
     */
    public function index(Request $request)
    {
        $includeComments = $request->query('includeComments');
        
        $comment = Post::paginate();

        if (filter_var($includeComments, FILTER_VALIDATE_BOOLEAN)) {
            $comment = Post::with('comments')->paginate();
        }

        return new PostCollection($comment);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        return new PostResource(Post::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->all());
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();

        if ($user !== null && $user->tokenCan('server:delete')) {

            if ($post->delete($post)) {
                return ['id' => $post->id];
            }

            return response(['delete' => 'Not deleted']);
        }

        throw ValidationException::withMessages([
            'token' => 'Permission denied'
        ]);
    }
}
