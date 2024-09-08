<?php

namespace Tests\Feature;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function getToken(): string
    {
        return $this->getJson('/setup')->json('admin');
    }

    public function test_get_all_posts_api_request(): void
    {
        $token = $this->getToken();

        $response = $this->getJson('/api/posts', ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
    }

    public function test_get_one_post_by_id_api_request(): void
    {
        $token = $this->getToken();

        Post::factory(1)->create();

        $post = Post::first();

        $response = $this->getJson('/api/posts/' . $post->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
    }

    public function test_create_an_post_api_request(): void
    {
        $token = $this->getToken();

        $newPosts = Post::factory(1)->make()->first();

        $newPost['userId'] = $newPosts->user_id;
        $newPost['title'] = $newPosts->title;
        $newPost['content'] = $newPosts->content;


        $response = $this->post('/api/posts', $newPost, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201);
    }

    public function test_delete_an_post_api_request(): void
    {
        $token = $this->getToken();

        $newPost = Post::factory(1)->create();

        $newPost = Post::first();

        $response = $this->getJson('/api/posts/' . $newPost->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
        $response->json('id');
    }
}
