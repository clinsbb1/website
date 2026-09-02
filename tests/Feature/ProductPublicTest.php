<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_product_with_a_case_study_is_accessible(): void
    {
        $product = Product::create([
            'name' => 'Example Product',
            'slug' => 'example-product',
            'short_description' => 'Does example things.',
            'overview' => 'A longer overview of the product.',
            'status' => Product::STATUS_LIVE,
            'published' => true,
            'case_study_enabled' => true,
        ]);

        $this->get(route('work.show', $product))
            ->assertOk()
            ->assertSee('Example Product');
    }

    public function test_unpublished_product_returns_404(): void
    {
        $product = Product::create([
            'name' => 'Hidden Product',
            'slug' => 'hidden-product',
            'short_description' => 'Not yet public.',
            'overview' => 'Overview text.',
            'status' => Product::STATUS_IN_DEVELOPMENT,
            'published' => false,
            'case_study_enabled' => true,
        ]);

        $this->get(route('work.show', $product))->assertNotFound();
    }

    public function test_published_product_without_a_case_study_returns_404_on_detail_page(): void
    {
        $product = Product::create([
            'name' => 'No Case Study',
            'slug' => 'no-case-study',
            'short_description' => 'Live, but no case study yet.',
            'status' => Product::STATUS_LIVE,
            'published' => true,
            'case_study_enabled' => false,
        ]);

        $this->get(route('work.show', $product))->assertNotFound();
    }

    public function test_unpublished_products_are_excluded_from_the_work_index(): void
    {
        Product::create([
            'name' => 'Visible', 'slug' => 'visible', 'short_description' => 'x',
            'status' => Product::STATUS_LIVE, 'published' => true,
        ]);
        Product::create([
            'name' => 'Invisible', 'slug' => 'invisible', 'short_description' => 'x',
            'status' => Product::STATUS_IN_DEVELOPMENT, 'published' => false,
        ]);

        $this->get(route('work.index'))->assertSee('Visible')->assertDontSee('Invisible');
    }
}
