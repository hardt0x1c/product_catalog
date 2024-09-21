<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 0]);
});

describe('show category', function () {
    it('returns a category resource', function () {
        $category = Category::factory()->create();

        Sanctum::actingAs($this->user, ['*']);

        $response = $this->getJson(route('categories.show', ['category' => $category->id]));
        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
            ]);
    });

    it('does not allow non-authenticated to get categories', function () {
        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.show', ['category' => $category->id]));
        $response->assertUnauthorized();
    });
});
