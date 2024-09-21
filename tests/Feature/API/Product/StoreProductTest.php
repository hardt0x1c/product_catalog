<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 0]);
    $this->category = Category::factory()->create();
});

describe('store product', function () {
    it('creates a new product', function () {
        $admin = User::factory()->create(['is_admin' => 1]);
        $productData = [
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100,
            'category_id' => $this->category->id,
        ];

        Sanctum::actingAs($admin, ['*']);

        $response = $this->postJson(route('products.store'), $productData);
        $response->assertCreated()
            ->assertJsonFragment([
                'name' => 'test name',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'category',
                ],
            ]);
    });

    it('does not allow non-admin to create a product', function () {
        $productData = [
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100,
            'category_id' => $this->category->id,
        ];

        Sanctum::actingAs($this->user, ['*']);

        $response = $this->postJson(route('products.store'), $productData);
        $response->assertForbidden();
    });
});
