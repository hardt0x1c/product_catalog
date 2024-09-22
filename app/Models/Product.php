<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Product",
 *     required={"name", "price"},
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Product ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Product name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Product description"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="integer",
 *         description="Product price"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category",
 *         description="Category that the product belongs to"
 *     ),
 * )
 */
final class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * @var list<string>
     */
    protected $with = [
        'category',
    ];

    /**
     * @return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
