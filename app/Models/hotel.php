<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\hotelfacil;
use App\Models\room;
use Whoops\Run;

class hotel extends Model
{
    use HasFactory;
    protected $table = 'hotels';
    protected $fillable = ['id', 'nama', 'alamat', 'lat', 'long', 'thumbnail'];
    // protected $hide = ['created_at', 'updated_at'];

    public function fasilitas()
    {
        return $this->hasMany(hotelfacil::class);
    }
    
    public function fa()
    {
        return $this->hasMany(hotelfacil::class)->get('facil');
    }

    public function rooms()
    {
        return $this->hasMany(room::class);
    }
}
