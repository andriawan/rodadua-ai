<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'part_number',
        'oem_number',
        'category',
        'subcategory',
        'brand',
        'compatible_motorcycles',
        'compatible_years',
        'retail_price',
        'wholesale_price',
        'quantity_available',
        'in_stock',
        'supplier_name',
        'supplier_code',
        'notes',
        'view_count',
    ];

    protected $casts = [
        'compatible_motorcycles' => 'json',
        'compatible_years' => 'json',
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'in_stock' => 'boolean',
    ];

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeSearchByPartNumber($query, $partNumber)
    {
        return $query->where('part_number', 'like', "%{$partNumber}%")
                    ->orWhere('oem_number', 'like', "%{$partNumber}%");
    }
}
