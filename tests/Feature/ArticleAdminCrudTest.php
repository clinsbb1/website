<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function content(): string
    {
        return json_encode(['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Some content.']]],
        ]]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'A New Article',
            'slug' => '',
            'excerpt' => 'An excerpt.',
            'content_json' => $this->content(),
            'status' => Article::STATUS_DRAFT,
            'published_at' => '',
            'action' => 'save',
        ], $overrides);
    }

    public function test_admin_can_create_an_article_with_a_valid_tiptap_document(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.articles.store'), $this->payload());

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', ['title' => 'A New Article', 'slug' => 'a-new-article']);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        $user = User::factory()->create();
        Article::create([
            'title' => 'Existing', 'slug' => 'taken-slug',
            'content_json' => json_decode($this->content(), true),
            'status' => Article::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($user)->post(route('admin.articles.store'), $this->payload(['slug' => 'taken-slug']));

        $response->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('articles', 1);
    }

    public function test_publishing_with_empty_content_is_blocked(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.articles.store'), $this->payload([
            'status' => Article::STATUS_PUBLISHED,
            'content_json' => json_encode(['type' => 'doc', 'content' => []]),
        ]));

        $response->assertSessionHasErrors('content_json');
        $this->assertDatabaseMissing('articles', ['title' => 'A New Article']);
    }

    public function test_saving_an_empty_draft_is_allowed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.articles.store'), $this->payload([
            'status' => Article::STATUS_DRAFT,
            'content_json' => json_encode(['type' => 'doc', 'content' => []]),
        ]));

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', ['title' => 'A New Article', 'status' => Article::STATUS_DRAFT]);
    }
}
