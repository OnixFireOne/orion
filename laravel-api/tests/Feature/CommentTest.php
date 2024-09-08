<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function getToken(): string
    {
        return $this->getJson('/setup')->json('admin');
    }

    public function test_get_all_comments_api_request(): void
    {
        $token = $this->getToken();

        $response = $this->getJson('/api/comments', ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
    }

    public function test_get_one_comment_by_id_api_request(): void
    {
        $token = $this->getToken();

        Post::factory(1)->create();
        Comment::factory(1)->create();

        $comment = Comment::first();

        $response = $this->getJson('/api/comments/' . $comment->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
    }

    public function test_create_an_comment_api_request(): void
    {
        $token = $this->getToken();

        
        $newComment['content'] = 'add';
        $newComment['postId'] = 1;
        $newComment['userId'] = 1;

        $response = $this->post('/api/comments', $newComment, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201);
    }

    public function test_delete_an_comment_api_request(): void
    {
        $token = $this->getToken();


        Post::factory(1)->create();
        $newComment = Comment::factory(1)->create();

        $newComment = Comment::first();

        $response = $this->getJson('/api/comments/' . $newComment->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
        $response->json('id');
    }
}
