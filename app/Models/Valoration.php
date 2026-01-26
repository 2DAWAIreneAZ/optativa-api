<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_product',
        'user_id',
        'puntuation',
        'comment'
    ];

    protected $casts = [
        'puntuation' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}