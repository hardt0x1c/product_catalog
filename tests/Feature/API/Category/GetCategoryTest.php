<?php

declare(strict_types=1);

use App\Models\Category;

use function Pest\Laravel\getJson;

describe('show category', function () {
    it('returns a category resource', function () {
        $category = Category::factory()->create();

        getJson(route('categories.show', ['category' => $category->id]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
            ]);
    });
});
