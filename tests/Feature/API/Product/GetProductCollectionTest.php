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

    it('returns a collection of products sorted by price in ascending order', function () {
        $products = Product::factory()->createMany([
            ['price' => 200],
            ['price' => 100],
            ['price' => 300],
            ['price' => 150],
            ['price' => 250],
        ]);

        $response = getJson(route('products.index', ['sort' => 'price']))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);

        $prices = array_column($response->json('data'), 'price');

        $this->assertSame([100, 150, 200, 250, 300], $prices);
    });

    it('returns a collection of products sorted by price in descending order', function () {
        $products = Product::factory()->createMany([
            ['price' => 200],
            ['price' => 100],
            ['price' => 300],
            ['price' => 150],
            ['price' => 250],
        ]);

        $response = getJson(route('products.index', ['sort' => '-price']))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);

        $prices = array_column($response->json('data'), 'price');

        $this->assertSame([300, 250, 200, 150, 100], $prices);
    });
});
