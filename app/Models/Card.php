<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['uid'];

    public function user()
    {
        return $this->hasOne(User::class, 'id_card');
    }
}

