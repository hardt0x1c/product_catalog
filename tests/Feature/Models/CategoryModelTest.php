<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->category = Category::factory()->create();
    $this->products = Product::factory()->count(5)->create(['category_id' => $this->category->id]);
});

describe('Category Model', function () {
    it('can be created', function () {
        expect($this->category)->toBeInstanceOf(Category::class);
    });

    it('has products', function () {
        expect($this->category->products)->toBeInstanceOf(Collection::class);
    });
});
