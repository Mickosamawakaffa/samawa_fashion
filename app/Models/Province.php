<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'rajaongkir_id',
        'name',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
