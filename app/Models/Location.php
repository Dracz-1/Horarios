<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    
    protected $fillable = [
        'name',
        'reader',
    ];

    public function records()
    {
        return $this->hasMany(Record::class,'id_location');
    }
}