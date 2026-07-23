<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(private readonly ProductImageService $images)
    {
    }

    public function index(Request $request)
    {
        // Build query
        $productsQuery = Product::query();

        // Filter by category
        if ($request->filled('category')) {
            $productsQuery->whereRaw('LOWER(category) = ?', [mb_strtolower($request->input('category'))]);
        }
        if (! $request->filled('price')) {
            $productsQuery->latest();
        }

        // Filter by price range
        if ($request->filled('price')) {
            if ($request->input('price') === 'low') {
                $productsQuery->orderBy('price', 'asc');
            } elseif ($request->input('price') === 'high') {
                $productsQuery->orderBy('price', 'desc');
            }
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Paginate results
        $products = $productsQuery->paginate(12)->appends($request->query());
        $categories = collect(Product::CATEGORIES)
            ->merge(Product::query()->whereNotNull('category')->pluck('category'))
            ->filter()->unique(fn ($category) => mb_strtolower($category))->values();
        $cartQuantities = Auth::check()
            ? Cart::where('user_id', Auth::id())->pluck('quantity', 'product_id')->map(fn ($quantity) => (int) $quantity)
            : collect($request->session()->get('guest_cart', []));

        // Return view with filtered products
        return view('products', compact('products', 'categories', 'cartQuantities'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedProduct($request, true);
        $data = $this->resolveCategory($data);
        $product = Product::create($data);
        $product->update($this->images->store($request->file('image'), $product));
        ActivityLog::record('product.created', 'Product “'.$product->name.'” was created', $product, ['price' => $product->price]);

        return back()->with('success', 'Product added successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedProduct($request);
        $data = $this->resolveCategory($data);

        if ($request->hasFile('image')) {
            if ($product->image_public_id || str_starts_with((string) $product->image_url, '/storage/')) {
                $this->images->delete($product->image_url, $product->image_public_id);
            }
            $newImage = $this->images->store($request->file('image'), $product);
            $data = [...$data, ...$newImage];
        }

        $product->update($data);
        ActivityLog::record('product.updated', 'Product “'.$product->name.'” was updated', $product);

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        ActivityLog::record('product.deleted', 'Product “'.$product->name.'” was deleted', $product);
        $this->images->delete($product->image_url, $product->image_public_id, $product->id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    private function validatedProduct(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'category' => ['nullable', 'string', 'max:80'],
            'custom_category' => ['nullable', 'required_if:category,Other', 'string', 'max:80'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function resolveCategory(array $data): array
    {
        if (($data['category'] ?? null) === 'Other') {
            $data['category'] = trim($data['custom_category']);
        }

        unset($data['custom_category']);

        return $data;
    }

    public function image(Product $product)
    {
        $image = ProductImage::where('product_id', $product->id)->firstOrFail();

        return response(base64_decode($image->image_data, true), 200, [
            'Content-Type' => $image->mime_type,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

}
