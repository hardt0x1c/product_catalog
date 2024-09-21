<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('Auth Test', function () {
    it('can register', function () {
        $response = $this->postJson('api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'User Created Successfully',
            ]);
    });

    it('can login', function () {
        $user = User::factory()->create(
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]
        );
        $response = $this->postJson('api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'User Logged In Successfully',
            ]);
    });

    it('should return validation error when register', function () {
        $response = $this->postJson('api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment([
                'status' => false,
                'message' => 'validation error',
            ]);
    });

    it('should return validation error when login', function () {
        $response = $this->postJson('api/v1/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment([
                'status' => false,
                'message' => 'validation error',
            ]);
    });

    it('should return wrong password when login', function () {
        $user = User::factory()->create(
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]
        );
        $response = $this->postJson('api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ]);
    });
});
