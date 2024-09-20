<?php

declare(strict_types=1);

use App\Models\Product;

use function Pest\Laravel\getJson;

describe('show product', function () {
    it('returns a product', function () {
        $product = Product::factory()->create();

        getJson(route('products.show', ['product' => $product]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                ],
            ]);
    });
});
