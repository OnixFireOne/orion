<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentCollection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CommentCollection(Comment::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        return new CommentResource(Comment::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Comment $comment) {
        $user = $request->user();

        if ($user !== null && $user->tokenCan('server:delete')) {
            
            if ($comment->delete($comment)) {
                return ['id' => $comment->id];
            }

            return response(['delete' => 'Not deleted']);
        }

        throw ValidationException::withMessages([
            'token' => 'Permission denied'
        ]);
    }
}
