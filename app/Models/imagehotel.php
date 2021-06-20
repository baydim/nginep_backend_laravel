<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imagehotel extends Model
{
    use HasFactory;
    protected $table = 'imagehotels';
    protected $fillable = ['id', 'hotel_id', 'detail_image'];
}
