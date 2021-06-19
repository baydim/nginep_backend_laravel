<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomfacil extends Model
{
    use HasFactory;
    protected $table = 'roomfacils';
    protected $fillable = ['id', 'hotel_id', 'facil'];
}
