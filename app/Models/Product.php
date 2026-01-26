<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_style',
        'price',
        'description',
        'image',
        'stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    public function style()
    {
        return $this->belongsTo(Style::class, 'id_style');
    }

    public function valorations()
    {
        return $this->hasMany(Valoration::class, 'id_product');
    }

    public function averageRating()
    {
        return $this->valorations()->avg('puntuation');
    }
}
