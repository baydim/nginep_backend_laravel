<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotelfacil extends Model
{
    use HasFactory;
    protected $table = 'hotelfacils';
    protected $fillable = ['id', 'hotel_id', 'facil'];
}
