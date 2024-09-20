<?php

declare(strict_types=1);

use App\Models\Product;

use function Pest\Laravel\getJson;

describe('index category', function () {
    it('returns a collection of products', function () {
        $products = Product::factory()->count(5)->create();

        getJson(route('products.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);
    });
});
