<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('index products', function () {
    it('returns a collection of products', function () {
        $products = Product::factory()->count(5)->create();
        Sanctum::actingAs($this->user, ['*']);

        getJson(route('products.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);
    });

    it('does not allow non-authenticated to index products', function () {
        getJson(route('products.index'))
            ->assertUnauthorized();
    });

    it('returns a collection of products sorted by price in ascending order', function () {
        $products = Product::factory()->createMany([
            ['price' => 200],
            ['price' => 100],
            ['price' => 300],
            ['price' => 150],
            ['price' => 250],
        ]);

        Sanctum::actingAs($this->user, ['*']);

        $response = getJson(route('products.index', ['sort' => 'price']))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);

        $prices = array_column($response->json('data'), 'price');

        $this->assertSame([100, 150, 200, 250, 300], $prices);
    });

    it('returns a collection of products sorted by price in descending order', function () {
        $products = Product::factory()->createMany([
            ['price' => 200],
            ['price' => 100],
            ['price' => 300],
            ['price' => 150],
            ['price' => 250],
        ]);

        Sanctum::actingAs($this->user, ['*']);

        $response = getJson(route('products.index', ['sort' => '-price']))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);

        $prices = array_column($response->json('data'), 'price');

        $this->assertSame([300, 250, 200, 150, 100], $prices);
    });

    it('returns a collection of products filtered by price', function () {
        $products = Product::factory()->createMany([
            ['price' => 100],
            ['price' => 100],
            ['price' => 200],
            ['price' => 300],
            ['price' => 400],
        ]);

        Sanctum::actingAs($this->user, ['*']);

        $response = getJson(route('products.index', ['filter[price]' => 100]))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'category'],
                ],
            ]);

        $prices = array_column($response->json('data'), 'price');

        $this->assertSame([100, 100], $prices);
    });

    it('returns a collection of products filtered by category', function () {
        $categoryVeg = Category::factory()->create(['name' => 'Овощи']);
        $categoryFroots = Category::factory()->create(['name' => 'Фрукты']);

        $productVeg = Product::factory()->create([
            'name' => 'Морковь',
            'category_id' => $categoryVeg->id,
        ]);
        $productFroot = Product::factory()->create([
            'name' => 'Яблоко',
            'category_id' => $categoryFroots->id,
        ]);

        Sanctum::actingAs($this->user, ['*']);

        getJson(route('products.index', ['filter[category]' => 'Овощи']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Морковь'])
            ->assertJsonMissing(['name' => 'Яблоко']);
    });

    it('returns paginated a collection of products', function () {
        $products = Product::factory()->count(30)->create();

        Sanctum::actingAs($this->user, ['*']);

        getJson(route('products.index', ['per_page' => 10]))
            ->assertOk()
            ->assertJsonCount(10, 'data');
    });
});
