<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('search product', function () {
    it('returns products', function () {
        $products = Product::factory()->createMany([
            ['name' => 'Молоко'],
            ['name' => 'Хлеб'],
            ['name' => 'Яблоко'],
            ['name' => 'Помидор'],
            ['name' => 'Огурец'],
        ]);

        Sanctum::actingAs($this->user, ['*']);

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
    });

    it('does not allow non-authenticated to search products', function () {
        getJson(route('products.search', ['q' => 'Молоко']))
            ->assertUnauthorized();
    });
});
