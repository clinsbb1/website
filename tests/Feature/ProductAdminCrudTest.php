<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'A New Product',
            'slug' => '',
            'description' => 'Does new things.',
            'status' => Product::STATUS_LIVE,
        ], $overrides);
    }

    public function test_admin_can_create_a_product(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.products.store'), $this->payload());

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'A New Product', 'slug' => 'a-new-product']);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        $user = User::factory()->create();
        Product::create([
            'name' => 'Existing', 'slug' => 'taken-slug', 'description' => 'x', 'status' => Product::STATUS_LIVE,
        ]);

        $response = $this->actingAs($user)->post(route('admin.products.store'), $this->payload(['slug' => 'taken-slug']));

        $response->assertSessionHasErrors('slug');
        $this->assertDatabaseCount('products', 1);
    }

    public function test_editing_a_product_does_not_change_its_slug_unless_explicitly_edited(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Original Name', 'slug' => 'original-slug', 'description' => 'x', 'status' => Product::STATUS_LIVE,
        ]);

        $this->actingAs($user)->put(route('admin.products.update', $product), $this->payload([
            'name' => 'A Renamed Product',
            'slug' => 'original-slug',
        ]));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'A Renamed Product', 'slug' => 'original-slug']);
    }
}
