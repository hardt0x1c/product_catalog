<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    $this->product = Product::factory()->create();
});

describe('Product Model', function () {
    it('can be created', function () {
        expect($this->product)->toBeInstanceOf(Product::class);
    });

    it('has category', function () {
        expect($this->product->category)->toBeInstanceOf(Category::class);
    });
});
