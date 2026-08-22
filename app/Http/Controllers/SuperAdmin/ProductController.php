<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $products = Product::query()
            ->with(['category', 'options', 'variants', 'images'])
            ->latest()
            ->paginate(12);

        return Inertia::render('SuperAdmin/Store/Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $categories = Category::select('id', 'name')
            ->where('status', true)
            ->get();

        return Inertia::render('SuperAdmin/Store/Products/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        DB::beginTransaction();

        // Create Product
        try {
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'short_description' => $request->short_description,
                'description' => $request->description,
                'is_featured' => $request->is_featured ?? false,
                'is_active' => $request->is_active ?? true,
            ]);

            // Product Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $file) {

                    $path = $file->store('products', 'public');

                    $product->images()->create([
                        'image_path' => $path,
                        'is_main' => $i === 0,
                        'sort_order' => $i,
                    ]);
                }
            }

            // Options + Values
            $valueMap = [];

            if ($request->options) {

                foreach ($request->options as $option) {

                    $opt = $product->options()->create([
                        'name' => $option['name'],
                        'type' => $option['type']
                    ]);

                    foreach ($option['values'] as $value) {

                        $val = $opt->values()->create([
                            'value' => $value,
                        ]);

                        $valueMap[$option['name']][$value] = $val->id;
                    }
                }
            }

            // Varients
            if ($request->variants) {

                foreach ($request->variants as $variant) {

                    $created = $product->variants()->create([
                        'sku' => $variant['sku'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                    ]);

                    $attachIds = [];

                    foreach ($variant['options'] as $optionName => $value) {

                        if (isset($valueMap[$optionName][$value])) {
                            $attachIds[] = $valueMap[$optionName][$value];
                        }
                    }

                    $created->optionValues()->attach($attachIds);
                }
            }

            DB::commit();

            return Redirect::route('super_admin.products.index')
                ->with('success', 'Product created successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $product->load([
            'images',
            'category',
            'variants.optionValues.option',
            'category',
        ]);

        $categories = Category::select('id', 'name')
            ->where('status', true)
            ->get();

        return Inertia::render('SuperAdmin/Store/Products/Show', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $product->load([
            'images',
            'options.values',
            'variants.optionValues.option',
            'category',
        ]);

        return Inertia::render('SuperAdmin/Store/Products/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'short_description' => $request->short_description,
                'description' => $request->description,
                'is_featured' => $request->is_featured ?? false,
                'is_active' => $request->is_active ?? true,
            ]);

            // Handle new images (optional)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_main' => $i === 0,
                        'sort_order' => $i,
                    ]);
                }
            }

            $product->options()->delete();

            $valueMap = [];

            if ($request->options) {
                foreach ($request->options as $option) {
                    $opt = $product->options()->create([
                        'name' => $option['name'],
                        'type' => $option['type']
                    ]);

                    foreach ($option['values'] as $value) {
                        $val = $opt->values()->create([
                            'value' => $value,
                        ]);
                        $valueMap[$option['name']][$value] = $val->id;
                    }
                }
            }

            // Delete existing variants
            $product->variants()->delete();

            if ($request->variants) {
                foreach ($request->variants as $variant) {
                    $created = $product->variants()->create([
                        'sku' => $variant['sku'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                    ]);

                    $attachIds = [];

                    foreach ($variant['options'] as $optionName => $value) {
                        if (isset($valueMap[$optionName][$value])) {
                            $attachIds[] = $valueMap[$optionName][$value];
                        }
                    }

                    $created->optionValues()->attach($attachIds);
                }
            }

            DB::commit();

            return Redirect::route('super_admin.products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }
        
        DB::beginTransaction();

        try {
            foreach ($product->images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            $product->delete();

            DB::commit();

            return Redirect::back()->with('Product Deleted Successfully');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
