<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('User Model', function () {
    it('can be created', function () {
        expect($this->user)->toBeInstanceOf(User::class);
    });
});
