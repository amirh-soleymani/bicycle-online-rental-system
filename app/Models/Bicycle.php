<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicycle extends Model
{
    use HasFactory;

    protected $table = 'bicycles';

    protected $fillable = [
        'brand',
        'model',
        'color',
        'prod_year',
        'image'
    ];

    public function rent()
    {
        return $this->hasMany(Rent::class, 'bicycle_id', 'id');
    }
}
