<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *
     *             @OA\Property(property="name", type="string", description="The name of the user"),
     *             @OA\Property(property="email", type="string", format="email", description="The email of the user"),
     *             @OA\Property(property="password", type="string", format="password", description="The password of the user")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Created Successfully"),
     *             @OA\Property(property="token", type="string", example="token_example")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string", format="email", description="The user's email"),
     *             @OA\Property(property="password", type="string", format="password", description="The user's password")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Logged In Successfully"),
     *             @OA\Property(property="token", type="string", example="token_example")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Validation error or credentials mismatch",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="validation error or credentials do not match")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            if (! Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if (! $user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
