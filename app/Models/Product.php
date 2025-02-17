<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'stock',
    ];

    // Each product belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // If users can own products
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

