<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.categories.store'), ['name' => 'Engineering'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'Engineering', 'slug' => 'engineering']);
    }

    public function test_admin_can_rename_a_category_without_changing_its_slug(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Engineering', 'slug' => 'engineering']);

        $this->actingAs($user)->put(route('admin.categories.update', $category), ['name' => 'Software Engineering']);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id, 'name' => 'Software Engineering', 'slug' => 'engineering',
        ]);
    }

    public function test_deleting_a_category_uncategorises_its_articles_instead_of_deleting_them(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Engineering', 'slug' => 'engineering']);
        $article = Article::create([
            'title' => 'A Post', 'slug' => 'a-post', 'category_id' => $category->id,
            'content_json' => ['type' => 'doc', 'content' => []], 'status' => Article::STATUS_DRAFT,
        ]);

        $this->actingAs($user)->delete(route('admin.categories.destroy', $category));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'category_id' => null]);
    }
}
