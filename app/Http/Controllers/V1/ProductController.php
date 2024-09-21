<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $query = Product::query();

        $products = QueryBuilder::for($query)
            ->allowedSorts('price')
            ->allowedFilters([
                'price',
                AllowedFilter::callback('category', function ($query, $value) {
                    $query->whereHas('category', function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->with('category')
            ->paginate($request->input('per_page', 15))
            ->appends($request->query());

        return ProductResource::collection($products);
    }

    public function search(Request $request): ResourceCollection
    {
        $query = $request->input('q');
        $products = Product::where('name', 'LIKE', '%'.$query.'%')->get();

        return ProductResource::collection($products);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $validated = $request->except('category_id');

        $category = Category::findOrFail($request->input('category_id'));

        if ($category instanceof Category) {
            $product = $category->products()->create($validated);
        } else {
            throw new \Exception('Category not found');
        }

        $product->load('category');

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $validated = $request->validated();
        $product->update($validated);

        return new ProductResource($product->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response
    {
        $product->deleteOrFail();

        return response()->noContent();
    }
}
