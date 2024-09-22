<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Product Store API",
 *         description="API for managing products and categories",
 *
 *         @OA\Contact(
 *             email="support@example.com"
 *         ),
 *
 *         @OA\License(
 *             name="MIT",
 *             url="https://opensource.org/licenses/MIT"
 *         )
 *     ),
 *
 *     @OA\Server(
 *         url="http://localhost/api/v1",
 *         description="Local development server"
 *     )
 * )
 */
abstract class Controller
{
    //
}
