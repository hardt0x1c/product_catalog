<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     summary="Get a list of categories",
     *     tags={"Categories"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful request",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Category")
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
    public function index(): ResourceCollection
    {
        Gate::authorize('viewAny', Category::class);

        return CategoryResource::collection(Category::all());
    }

    /**
     * @OA\Get(
     *     path="/categories/{category}",
     *     summary="Get a single category",
     *     tags={"Categories"},
     *
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful request",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function show(Category $category): CategoryResource
    {
        Gate::authorize('view', $category);

        return new CategoryResource($category);
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Category successfully created",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Category")
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
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        Gate::authorize('create', Category::class);

        $validated = $request->validated();
        $category = Category::create($validated);

        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     *     path="/categories/{category}",
     *     summary="Update an existing category",
     *     tags={"Categories"},
     *
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Category successfully updated",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Category")
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
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource|JsonResponse
    {
        Gate::authorize('update', $category);

        $validated = $request->validated();
        $category->update($validated);

        return new CategoryResource($category->refresh());
    }

    /**
     * @OA\Delete(
     *     path="/categories/{category}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Category successfully deleted"
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
     *         description="Category not found"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy(Category $category): Response
    {
        Gate::authorize('delete', $category);

        $category->deleteOrFail();

        return response()->noContent();
    }
}
