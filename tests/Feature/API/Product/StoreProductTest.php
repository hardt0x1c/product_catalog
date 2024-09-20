<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
});

describe('store product', function () {
    it('creates a new product', function () {
        $productData = [
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100,
            'category_id' => $this->category->id,
        ];

        actingAs($this->user)
            ->postJson(route('products.store'), $productData)
            ->assertCreated()
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
});
