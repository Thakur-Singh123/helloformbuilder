<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductAttributesValues;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function sports()
    {
        $sports = Category::orderBy('title')
            ->get()
            ->filter(fn (Category $row) => $row->status !== 'inactive' && $row->status !== '0')
            ->map(fn (Category $row) => $this->sportPayload($row))
            ->values();

        return $this->ok(['sports' => $sports], 'Sports fetched successfully.');
    }

    public function index(Request $request)
    {
        $query = Products::with(['category', 'images'])
            ->where('status', 'approved');

        $sport = trim((string) $request->query('sport', ''));
        if ($sport !== '') {
            $category = $this->findSport($sport);
            if (!$category) {
                return $this->fail('Sport not found.', [], 404);
            }
            $query->where('category_id', $category->id);
        }

        $products = $query->orderBy('id', 'desc')
            ->get()
            ->map(fn (Products $product) => $this->listPayload($product))
            ->values();

        return $this->ok([
            'sport' => $sport !== '' ? $this->sportPayload($this->findSport($sport)) : null,
            'products' => $products,
        ], 'Products fetched successfully.');
    }

    public function show($id)
    {
        $product = Products::with(['variations', 'images', 'category'])
            ->where('status', 'approved')
            ->find($id);
        if (!$product) {
            return $this->fail('Product not found.', [], 404);
        }

        return $this->ok($this->detailPayload($product), 'Product fetched successfully.');
    }

    private function findSport(string $sport): ?Category
    {
        $query = Category::query();
        if (is_numeric($sport)) {
            return $query->find((int) $sport);
        }

        $sport = strtolower($sport);

        return $query->where(function ($inner) use ($sport) {
                $inner->whereRaw('LOWER(slug) = ?', [$sport])
                    ->orWhereRaw('LOWER(title) = ?', [$sport])
                    ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . $sport . '%'])
                    ->orWhereRaw('LOWER(title) LIKE ?', ['%' . $sport . '%']);
            })
            ->orderBy('id')
            ->first();
    }

    private function sportPayload(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->title,
            'slug' => $category->slug,
            'image_url' => $category->icon
                ? url('public/uploads/category/' . $category->icon)
                : ($category->image ? url('public/uploads/category/' . $category->image) : null),
        ];
    }

    private function listPayload(Products $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sport' => $product->category ? $product->category->slug : null,
            'sport_name' => $product->category ? $product->category->title : null,
            'image_url' => $this->productImageUrl($product->image),
            'mrp_price' => (float) $product->mrp_price,
            'sale_price' => (float) $product->sale_price,
            'currency' => 'USD',
            'team_order' => $product->team_order === 'yes',
            'can_customize' => $this->canCustomize($product),
        ];
    }

    private function detailPayload(Products $product): array
    {
        $variantAttributes = [];
        foreach ($product->variations as $variant) {
            $attrs = json_decode($variant->attributes, true) ?: [];
            foreach ($attrs as $key => $value) {
                $name = trim((string) $key);
                $variantAttributes[$name][] = trim((string) $value);
            }
        }
        foreach ($variantAttributes as $name => $values) {
            $variantAttributes[$name] = array_values(array_unique($values));
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'sport' => $product->category ? $product->category->slug : null,
            'sport_name' => $product->category ? $product->category->title : null,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'image_url' => $this->productImageUrl($product->image),
            'gallery' => $product->images
                ->map(fn ($row) => $this->productImageUrl($row->image))
                ->filter()
                ->values(),
            'mrp_price' => (float) $product->mrp_price,
            'sale_price' => (float) $product->sale_price,
            'currency' => 'USD',
            'team_order' => $product->team_order === 'yes',
            'can_customize' => $this->canCustomize($product),
            'has_svg' => !empty($product->svg_image),
            'svg' => $product->svg_image ?: null,
            'colors' => $this->colorSlots($product),
            'sizes' => $this->sizeOptions($product),
            'attributes' => $variantAttributes,
            'variants' => $product->variations->map(fn ($variant) => [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'price' => (float) $variant->price,
                'stock' => (int) $variant->stock,
                'attributes' => json_decode($variant->attributes, true) ?: [],
            ])->values(),
            'product_url' => url('product/' . $product->slug),
        ];
    }

    private function colorSlots(Products $product): array
    {
        $ids = array_values(array_filter(array_map('trim', explode(',', (string) $product->colors))));
        if (!$ids) {
            return [];
        }

        $rows = ProductAttributesValues::whereIn('id', $ids)->get()->keyBy('id');
        $slots = [];
        foreach ($ids as $index => $id) {
            $row = $rows->get((int) $id);
            $hex = $row->color_code ?? '#FFFFFF';
            $slots[] = [
                'id' => (int) $id,
                'key' => $index === 0 ? 'primary' : ($index === 1 ? 'secondary' : 'color_' . ($index + 1)),
                'name' => $row->value ?? ('Color ' . ($index + 1)),
                'hex' => $hex,
            ];
        }

        return $slots;
    }

    private function sizeOptions(Products $product): array
    {
        $sizes = array_values(array_filter(array_map('trim', explode(',', (string) $product->size))));

        return $sizes ?: [];
    }

    private function canCustomize(Products $product): bool
    {
        return !empty($product->svg_image) || !empty(trim((string) $product->colors));
    }

    private function productImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }
        if (str_contains($image, 'uploads/') || str_starts_with($image, 'http')) {
            return str_starts_with($image, 'http') ? $image : url($image);
        }

        return url('public/uploads/product/' . ltrim($image, '/'));
    }

    private function ok($data, string $message, int $code = 200)
    {
        $response = ['success' => true, 'message' => $message];
        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    private function fail(string $message, $errors = [], int $code = 400)
    {
        $response = ['success' => false, 'message' => $message];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
