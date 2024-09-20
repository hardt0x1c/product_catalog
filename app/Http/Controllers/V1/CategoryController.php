<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

final class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $validated = $request->validated();
        $category = Category::create($validated);

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource|JsonResponse
    {
        $validated = $request->validated();
        $category->update($validated);

        return new CategoryResource($category->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): Response
    {
        $category->deleteOrFail();

        return response()->noContent();
    }
}
