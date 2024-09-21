<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->user = User::factory()->create(['is_admin' => 0]);
    $this->product = Product::factory()->create();
});

describe('destroy product', function () {
    it('destroys a product', function () {
        Sanctum::actingAs(User::factory()->create(['is_admin' => 1]), ['*']);

        $response = $this->deleteJson(route('products.destroy', ['product' => $this->product]));

        $response->assertNoContent();
    });

    it('does not allow non-admin to destroy a product', function () {
        Sanctum::actingAs($this->user, ['*']);

        $response = $this->deleteJson(route('products.destroy', ['product' => $this->product]));

        $response->assertForbidden();
    });
});
