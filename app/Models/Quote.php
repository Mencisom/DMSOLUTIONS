<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quote extends Model
{
    use HasFactory;
    protected $fillable =
        [
            'client_name',
            'total',
            'status'
        ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'quote_client_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'quote_materials')->withPivot('quantity','total_price');
    }
    public function project()
    {
        return $this->hasOne(Project::class, 'quote_id');
    }

}
