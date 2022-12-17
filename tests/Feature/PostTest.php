<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    
    public function testNoBlogPostTextVisibleWhenNoPosts()
    {
        $response = $this->actingAs($this->user())
                         ->get(route('posts.index'));

        $response->assertSeeText('No Post found!');
        $response->assertStatus(200);
    }

    public function testSeeOnePostWhenOneExists()
    {

        $post = $this->actingAs($this->user())
                      ->createPost();

        $response = $this->get(route('posts.index'));

        $this->assertDatabaseHas('blog_posts', [
            'title' => "Title",
            'content' => "Content",
        ]);

        $response->assertDontSeeText('No Post found!');
        $response->assertSee("Title");
        $response->assertStatus(200);
    }

    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'Valid content'
        ];

        $this->actingAs($this->user())
            ->post(route('posts.store'), $params)
            ->assertStatus(302)
            ->assertSessionHas('status', 'The blog post was created!');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Valid title',
            'content' => 'Valid content'
        ]);

       
        
    }

    public function testStoreInvalidTitleTooShort()
    {
        $params = [
            'title' => 'titl',
            'content' => 'Valid content'
        ];

        $this->actingAs($this->user())
            ->post(route('posts.store'), $params)
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'title' => 'The title must be at least 5 characters.'
            ]);

        $this->assertDatabaseMissing('blog_posts', [
            'title' => 'titl',
            'content' => 'Valid content'
        ]);

       
        
    }

    public function testUpdateValid()
    {
        $user = $this->user();
        $post = $this->createPost($user->id);

        $params = [
            'title' => 'New title',
            'content' => 'New content'
        ];

        $this->actingAs($user)
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status', 'Blog post was updated.');
        
        $this->assertDatabaseMissing('blog_posts', [
            'title' => "Title",
            'content' => "Content"
        ]);

        $this->assertDatabaseHas('blog_posts', [
            'title' => "New title",
            'content' => "New content"
        ]); 
    }

    public function testDeleteWorks() {

        $user = $this->user();
        $post =  $this->createPost($user->id);

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertSoftDeleted('blog_posts', [
            'title' => "Title",
            'content' => "Content"
        ]);
    }

    public function testCommentMessageWorks() {

        //create a post with one comment
        $post = $this->actingAs($this->user())
                      ->createPost();
        
        //verify see No comments yet when no comments exists for a post
        $response = $this->get(route('posts.index'));
        $response->assertSeeText('No comments yet');
        $response->assertStatus(200);

        //verify see '1 comment' when only a single comment for a post
        $comment =$this->createComment();
        $post->comments()->save($comment);

        $response = $this->get(route('posts.index'));
        $response->assertSeeText('1 comment');
        $response->assertStatus(200);

        //verify see '2 comments' when two comments for a post
        $comment2 =$this->createComment();
        $post->comments()->save($comment2);

        $response = $this->get(route('posts.index'));
        $response->assertSeeText('2 comments');
        $response->assertStatus(200);
    }

    private function createPost($userId = null) : BlogPost {
        return BlogPost::factory()->testing()->create([
            'user_id' => $userId ?? $this->user()->id,
        ]);
    }

    private function createComment() {
       return Comment::factory()->make();  
    }

}
