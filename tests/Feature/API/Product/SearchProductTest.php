<?php

declare(strict_types=1);

use App\Models\Product;

use function Pest\Laravel\getJson;

describe('search product', function () {
    it('returns products', function () {
        $products = Product::factory()->createMany([
            ['name' => 'Молоко'],
            ['name' => 'Хлеб'],
            ['name' => 'Яблоко'],
            ['name' => 'Помидор'],
            ['name' => 'Огурец'],
        ]);

        getJson(route('products.search', ['q' => 'Молоко']))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price'],
                ],
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Молоко',
            ]);
    })->only();
});
