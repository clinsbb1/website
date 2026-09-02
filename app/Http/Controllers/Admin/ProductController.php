<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
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

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('products', 'public');
        }

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

        if ($request->boolean('remove_image')) {
            $this->deleteFile($product->image);
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteFile($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->boolean('remove_og_image')) {
            $this->deleteFile($product->og_image);
            $data['og_image'] = null;
        } elseif ($request->hasFile('og_image')) {
            $this->deleteFile($product->og_image);
            $data['og_image'] = $request->file('og_image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $this->deleteFile($product->image);
        $this->deleteFile($product->og_image);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    private function prepare(ProductRequest $request): array
    {
        $data = $request->safe()->except(['image', 'og_image', 'remove_image', 'remove_og_image', 'technologies']);
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['case_study_enabled'] = $request->boolean('case_study_enabled');
        $data['sort_order'] = $request->integer('sort_order');
        $data['technologies'] = $request->filled('technologies')
            ? array_values(array_filter(array_map('trim', explode(',', $request->string('technologies')))))
            : null;

        return $data;
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
