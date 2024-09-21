<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 0]);
});

describe('update category', function () {
    it('updates a category', function () {
        $category = Category::factory()->create();
        $categoryData = [
            'name' => 'test name',
        ];

        Sanctum::actingAs(User::factory()->create(['is_admin' => 1]), ['*']);

        $response = $this->patchJson(route('categories.update', ['category' => $category]), $categoryData);
        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'test name',
            ]);
    });

    it('does not allow non-admin to update a category', function () {
        $category = Category::factory()->create();
        $categoryData = [
            'name' => 'test name',
        ];

        Sanctum::actingAs($this->user, ['*']);

        $response = $this->patchJson(route('categories.update', ['category' => $category]), $categoryData);
        $response->assertForbidden();
    });
});
