<?php

declare(strict_types=1);

use App\Models\Category;

use function Pest\Laravel\getJson;

describe('index category', function () {
    it('returns a collection of categories', function () {
        $categories = Category::factory()->count(5)->create();

        getJson(route('categories.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
            ]);
    });
});
