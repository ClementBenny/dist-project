<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'bulk_price',
        'unit',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'      => 'decimal:2',
            'bulk_price' => 'decimal:2',
            'is_active'  => 'boolean',
            'stock'      => 'integer',
        ];
    }
}