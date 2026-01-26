<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'difficulty'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id_style');
    }
}