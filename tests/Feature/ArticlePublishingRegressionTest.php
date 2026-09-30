<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticlePublishingRegressionTest extends TestCase
{
    use RefreshDatabase;

    private function doc(string $text): string
    {
        return json_encode(['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => $text]]],
        ]]);
    }

    public function test_a_failed_save_keeps_what_was_written_in_the_editor(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('admin.articles.create'))
            ->followingRedirects()
            ->post(route('admin.articles.store'), [
                'title' => 'A Long Post',
                'status' => Article::STATUS_PUBLISHED,
                'excerpt' => str_repeat('x', 300), // over the 255 limit, so the save fails
                'content_json' => $this->doc('Three hours of careful writing.'),
            ]);

        $response->assertSee('Three hours of careful writing.', false);
        $response->assertSee('The excerpt field must not be greater than 255 characters.');
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_validation_errors_use_readable_field_names(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('admin.articles.create'))
            ->post(route('admin.articles.store'), [
                'title' => 'No content',
                'status' => Article::STATUS_PUBLISHED,
                'content_json' => json_encode(['type' => 'doc', 'content' => []]),
            ])
            ->assertSessionHasErrors('content_json');

        $this->followingRedirects()
            ->actingAs($user)
            ->get(route('admin.articles.create'))
            ->assertOk();
    }

    public function test_inline_image_upload_returns_a_host_independent_url(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('admin.articles.images.store'), [
            'image' => UploadedFile::fake()->image('photo.png'),
        ]);

        $response->assertOk();
        $url = $response->json('url');
        $this->assertStringStartsWith('/storage/articles/inline/', $url);
        Storage::disk('public')->assertExists(substr($url, strlen('/storage/')));
    }

    public function test_uploaded_images_are_served_without_a_storage_symlink(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('photo.png')->store('articles/inline', 'public');

        $this->get('/storage/'.$path)
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeaderMissing('Set-Cookie');
    }

    public function test_only_article_images_can_be_fetched_through_the_storage_route(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('secret.txt', 'nope');
        Storage::disk('public')->put('articles/notes.txt', 'nope');

        $this->get('/storage/secret.txt')->assertNotFound();
        $this->get('/storage/articles/notes.txt')->assertNotFound();
        $this->get('/storage/articles/missing.png')->assertNotFound();
        $this->get('/storage/articles/../secret.txt')->assertNotFound();
    }

    public function test_keepalive_requires_authentication(): void
    {
        $this->get(route('admin.keepalive'))->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.keepalive'))
            ->assertNoContent();
    }
}
