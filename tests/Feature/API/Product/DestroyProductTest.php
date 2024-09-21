<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create();
});

describe('destroy product', function () {
    it('destroys a product', function () {
        actingAs($this->user)
            ->deleteJson(route('products.destroy', ['product' => $this->product]))
            ->assertNoContent();
    });
});
