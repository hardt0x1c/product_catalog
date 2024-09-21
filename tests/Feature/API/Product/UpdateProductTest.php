<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 0]);
    $this->category = Category::factory()->create();
});

describe('update product', function () {
    it('updates a product', function () {
        $admin = User::factory()->create(['is_admin' => 1]);
        $product = Product::factory()->create();
        $productData = [
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100,
        ];

        Sanctum::actingAs($admin, ['*']);

        $response = $this->patchJson(route('products.update', ['product' => $product]), $productData);
        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'test name',
                'description' => 'test description',
                'price' => 100,
            ]);
    });

    it('does not allow non-admin to update a product', function () {
        $product = Product::factory()->create();
        $productData = [
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100,
        ];

        Sanctum::actingAs($this->user, ['*']);

        $response = $this->patchJson(route('products.update', ['product' => $product]), $productData);
        $response->assertForbidden();
    });
});
