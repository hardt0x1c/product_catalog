<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
});

describe('destroy category', function () {
    it('destroys a category', function () {
        actingAs($this->user)
            ->deleteJson(route('categories.destroy', ['category' => $this->category]))
            ->assertNoContent();
    });
});
