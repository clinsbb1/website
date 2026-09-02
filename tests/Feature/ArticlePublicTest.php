<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlePublicTest extends TestCase
{
    use RefreshDatabase;

    private function content(): array
    {
        return ['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello world']]],
        ]];
    }

    public function test_published_article_is_publicly_visible(): void
    {
        $article = Article::create([
            'title' => 'A Published Post',
            'slug' => 'a-published-post',
            'content_json' => $this->content(),
            'status' => Article::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('writing.show', $article))
            ->assertOk()
            ->assertSee('A Published Post')
            ->assertSee('Hello world');
    }

    public function test_draft_article_returns_404(): void
    {
        $article = Article::create([
            'title' => 'A Draft Post',
            'slug' => 'a-draft-post',
            'content_json' => $this->content(),
            'status' => Article::STATUS_DRAFT,
        ]);

        $this->get(route('writing.show', $article))->assertNotFound();
    }

    public function test_future_dated_article_returns_404(): void
    {
        $article = Article::create([
            'title' => 'A Future Post',
            'slug' => 'a-future-post',
            'content_json' => $this->content(),
            'status' => Article::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('writing.show', $article))->assertNotFound();
    }

    public function test_draft_and_future_articles_are_excluded_from_the_archive(): void
    {
        Article::create([
            'title' => 'Visible', 'slug' => 'visible', 'content_json' => $this->content(),
            'status' => Article::STATUS_PUBLISHED, 'published_at' => now()->subDay(),
        ]);
        Article::create([
            'title' => 'Hidden Draft', 'slug' => 'hidden-draft', 'content_json' => $this->content(),
            'status' => Article::STATUS_DRAFT,
        ]);

        $response = $this->get(route('writing.index'));

        $response->assertSee('Visible')->assertDontSee('Hidden Draft');
    }
}
