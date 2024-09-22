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
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Get a list of products",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of products per page",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful request",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function index(Request $request): ResourceCollection
    {
        Gate::authorize('viewAny', Product::class);

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

    /**
     * @OA\Get(
     *     path="/products/search",
     *     summary="Search products by name",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Query string for product search",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful request",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function search(Request $request): ResourceCollection
    {
        Gate::authorize('viewAny', Product::class);

        $query = $request->input('q');
        $products = Product::where('name', 'LIKE', '%'.$query.'%')->get();

        return ProductResource::collection($products);
    }

    /**
     * @OA\Get(
     *     path="/products/{product}",
     *     summary="Get a single product",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful request",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function show(Product $product): ProductResource
    {
        Gate::authorize('view', $product);

        return new ProductResource($product);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product successfully created",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        Gate::authorize('create', Product::class);

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
     * @OA\Put(
     *     path="/products/{product}",
     *     summary="Update an existing product",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product successfully updated",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();
        $product->update($validated);

        return new ProductResource($product->refresh());
    }

    /**
     * @OA\Delete(
     *     path="/products/{product}",
     *     summary="Delete a product",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Product successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy(Product $product): Response
    {
        Gate::authorize('delete', $product);

        $product->deleteOrFail();

        return response()->noContent();
    }
}
