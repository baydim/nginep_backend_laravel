<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\roomfacil;
use App\Models\imageroom;

class room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $fillable = ['id', 'hotel_id', 'nama', 'kasur', 'status', 'deskripsi', 'harga', 'thumbnail', 'alamat', 'lat', 'long'];

    public function fasilitas()
    {
        return $this->hasMany(roomfacil::class);
    }

    public function image_room()
    {
        return $this->hasMany(imageroom::class);
    }

    public function hotel()
    {
        return $this->hasMany(hotelfacil::class);
    }
}
