<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imageroom extends Model
{
    use HasFactory;
    protected $table = 'imagerooms';
    protected $fillable = ['id', 'room_id', 'detail_image'];
}
