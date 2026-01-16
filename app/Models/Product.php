<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['seller_id', 'name', 'description'];

    public function brands()
    {
        return $this->hasMany(ProductBrand::class);
    }

}
