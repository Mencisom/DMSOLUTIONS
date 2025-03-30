<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function quotes()
    {
        return $this->belongsToMany(Quote::class, 'quote_materials')->withPivot('quantity');
    }
}
