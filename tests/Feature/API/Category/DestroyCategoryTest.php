<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 1]);
    $this->category = Category::factory()->create();
});

describe('destroy category', function () {
    it('destroys a category', function () {
        Sanctum::actingAs($this->user, ['*']);

        $response = $this->deleteJson(route('categories.destroy', ['category' => $this->category]));
        $response->assertNoContent();
    });

    it('does not allow non-admin to destroy a category', function () {
        Sanctum::actingAs(User::factory()->create(['is_admin' => 0]), ['*']);

        $response = $this->deleteJson(route('categories.destroy', ['category' => $this->category]));
        $response->assertForbidden();
    });
});
