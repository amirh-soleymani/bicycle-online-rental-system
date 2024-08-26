<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rent extends Model
{
    use HasFactory;

    protected $table = 'rent';

    protected $fillable = [
        'bicycle_id',
        'user_id',
        'pickup_date',
        'return_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bicycle()
    {
        return $this->belongsTo(Bicycle::class, 'bicycle_id', 'id');
    }

}
