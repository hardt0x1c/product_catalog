<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 0]);
});

describe('index category', function () {
    it('returns a collection of categories', function () {
        $categories = Category::factory()->count(5)->create();

        Sanctum::actingAs($this->user, ['*']);

        $response = $this->getJson(route('categories.index'));
        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
            ]);
    });

    it('does not allow non-authenticated to get categories', function () {
        $response = $this->getJson(route('categories.index'));
        $response->assertUnauthorized();
    });
});
