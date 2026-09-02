<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_product_appears_on_the_work_index(): void
    {
        Product::create([
            'name' => 'Example Product',
            'slug' => 'example-product',
            'description' => 'Does example things.',
            'status' => Product::STATUS_LIVE,
            'published' => true,
        ]);

        $this->get(route('work.index'))
            ->assertOk()
            ->assertSee('Example Product');
    }

    public function test_unpublished_products_are_excluded_from_the_work_index(): void
    {
        Product::create([
            'name' => 'Visible', 'slug' => 'visible', 'description' => 'x',
            'status' => Product::STATUS_LIVE, 'published' => true,
        ]);
        Product::create([
            'name' => 'Invisible', 'slug' => 'invisible', 'description' => 'x',
            'status' => Product::STATUS_IN_DEVELOPMENT, 'published' => false,
        ]);

        $this->get(route('work.index'))->assertSee('Visible')->assertDontSee('Invisible');
    }

    public function test_unpublished_products_are_excluded_from_the_homepage(): void
    {
        Product::create([
            'name' => 'Featured And Published', 'slug' => 'featured-published', 'description' => 'x',
            'status' => Product::STATUS_LIVE, 'published' => true, 'featured' => true,
        ]);
        Product::create([
            'name' => 'Featured But Unpublished', 'slug' => 'featured-unpublished', 'description' => 'x',
            'status' => Product::STATUS_LIVE, 'published' => false, 'featured' => true,
        ]);

        $this->get(route('home'))
            ->assertSee('Featured And Published')
            ->assertDontSee('Featured But Unpublished');
    }
}
