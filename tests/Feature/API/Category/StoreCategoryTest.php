<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('store category', function () {
    it('creates a new category', function () {
        $categoryData = [
            'name' => 'test name',
        ];

        Sanctum::actingAs(User::factory()->create(['is_admin' => 1]), ['*']);

        $response = $this->postJson(route('categories.store'), $categoryData);
        $response->assertCreated()
            ->assertJsonFragment([
                'name' => 'test name',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ]);
    });

    it('does not allow non-admin to create a category', function () {
        $categoryData = [
            'name' => 'test name',
        ];

        Sanctum::actingAs(User::factory()->create(['is_admin' => 0]), ['*']);

        $response = $this->postJson(route('categories.store'), $categoryData);
        $response->assertForbidden();
    });
});
