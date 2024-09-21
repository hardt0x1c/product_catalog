<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
});

describe('update product', function () {
    it('updates a product', function () {
        $product = Product::factory()->create();
        $productData = [
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100,
        ];

        actingAs($this->user)
            ->patchJson(route('products.update', ['product' => $product]), $productData)
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'test name',
                'description' => 'test description',
                'price' => 100,
            ]);
    });
});
