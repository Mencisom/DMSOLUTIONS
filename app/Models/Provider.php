<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function product()
    {
        return $this->belongsToMany(Product::class, 'provider_id');
    }
}
