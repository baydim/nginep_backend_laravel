<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hoteltextfacils extends Model
{
    use HasFactory;
    protected $table = 'hoteltextfacils';
    protected $fillable = ['id', 'hotel_id', 'facil'];
}
