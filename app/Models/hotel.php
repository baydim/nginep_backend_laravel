<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\hotelfacil;
use App\Models\room;
use App\Models\imagehotel;


class hotel extends Model
{
    use HasFactory;
    protected $table = 'hotels';
    protected $fillable = ['id', 'nama', 'alamat', 'lat', 'long', 'thumbnail','deskripsi'];
    // protected $hide = ['created_at', 'updated_at'];


    public function image_hotel()
    {
        return $this->hasMany(imagehotel::class);
    }
    public function fasilitas()
    {
        return $this->hasMany(hotelfacil::class);
    }

    public function rooms()
    {
        return $this->hasMany(room::class);
    }
}
