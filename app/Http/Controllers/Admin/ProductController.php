<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::ordered()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product;

        return view('admin.products.form', compact('product'));
    }

    public function store(ProductRequest $request)
    {
        $data = $this->prepare($request);
        $data['slug'] = $data['slug'] ?: Str::slug($request->string('name'));

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $this->prepare($request);
        // The slug is only ever what the admin explicitly submits — never
        // silently regenerated from the name on update.
        $data['slug'] = $data['slug'] ?: $product->slug;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    private function prepare(ProductRequest $request): array
    {
        $data = $request->safe()->only(['name', 'slug', 'description', 'status', 'website_url']);
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['sort_order'] = $request->integer('sort_order');

        return $data;
    }
}
