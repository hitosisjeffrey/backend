<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index route.
     *
     * @return void
     */
    public function test_blog_index()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'api');

        Blog::factory()->count(3)->create();

        $response = $this->getJson('/api/blogs');

        $response->assertStatus(200);

        $response->assertJsonCount(3);
    }

    /**
     * Test the show route.
     *
     * @return void
     */
    public function test_blog_show()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'api');
        $blog = Blog::factory()->create();
        $response = $this->getJson("/api/blogs/{$blog->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $blog->id,
            'title' => $blog->title,
            'content' => $blog->content,
            'status' => $blog->status,
            'created_by' => $blog->created_by,
        ]);
    }

    /**
     * Test the store route (Create a new blog).
     *
     * @return void
     */
    public function test_blog_store()
    {

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'api');

        $data = [
            'title' => 'New Blog',
            'content' => 'This is a new blog.',
            'status' => Blog::STATUS_DRAFT,
        ];

        $response = $this->postJson('/api/blogs', $data);

        $response->assertStatus(201);

        $response->assertJsonFragment([
            'title' => 'New Blog',
            'content' => 'This is a new blog.',
            'status' => Blog::STATUS_DRAFT,
        ]);

        $this->assertDatabaseHas('blogs', [
            'title' => 'New Blog',
            'content' => 'This is a new blog.',
            'status' => Blog::STATUS_DRAFT,
        ]);
    }

    /**
     * Test the update route (Update a blog).
     *
     * @return void
     */
    public function test_blog_update()
    {
        $user = \App\Models\User::factory()->create();
        $blog = Blog::factory()->create();

        $data = [
            'title' => 'Updated Blog',
            'content' => 'This is the updated content.',
            'status' => Blog::STATUS_PUBLISHED,
        ];

        $response =  $this->actingAs($user, 'api')->putJson("/api/blogs/{$blog->id}", $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'title' => 'Updated Blog',
            'content' => 'This is the updated content.',
            'status' => Blog::STATUS_PUBLISHED,
        ]);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Blog',
            'content' => 'This is the updated content.',
            'status' => Blog::STATUS_PUBLISHED,
        ]);
    }

    /**
     * Test the destroy route (Delete a blog).
     *
     * @return void
     */
    public function test_blog_destroy()
    {
        $user = \App\Models\User::factory()->create();
        $blog = Blog::factory()->create();

        $response = $this->actingAs($user, 'api') 
        ->deleteJson("/api/blogs/{$blog->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
    }
}
