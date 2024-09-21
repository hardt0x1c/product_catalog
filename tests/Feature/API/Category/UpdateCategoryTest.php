<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('update category', function () {
    it('updates a category', function () {
        $category = Category::factory()->create();
        $categoryData = [
            'name' => 'test name',
        ];

        actingAs($this->user)
            ->patchJson(route('categories.update', ['category' => $category]), $categoryData)
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'test name',
            ]);
    });
});
