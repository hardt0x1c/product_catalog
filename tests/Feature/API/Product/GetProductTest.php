<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('show product', function () {
    it('returns a product', function () {
        $product = Product::factory()->create();

        Sanctum::actingAs($this->user, ['*']);

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

    it('does not allow non-authenticated to show product', function () {
        getJson(route('products.show', ['product' => 1]))
            ->assertUnauthorized();
    });
});
