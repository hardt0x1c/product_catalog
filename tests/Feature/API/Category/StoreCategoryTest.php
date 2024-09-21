<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('store category', function () {
    it('creates a new category', function () {
        $categoryData = [
            'name' => 'test name',
        ];

        actingAs($this->user)
            ->postJson(route('categories.store'), $categoryData)
            ->assertCreated()
            ->assertJsonFragment([
                'name' => 'test name',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ],
            ]);
    });
});
