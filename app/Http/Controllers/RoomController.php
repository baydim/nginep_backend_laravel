<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\room;
use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\roomfacil;

class RoomController extends Controller
{
    public function detail($id)
    {
        $data = room::find($id);
        if ($data == null) {
            return response()->json(['data' => $data]);
        } else {
            /////facilkamar
            $fasilitas_room = roomfacil::where('room_id', $data->id)->get();
            $roomid = [];
            foreach ($fasilitas_room as $fr => $from) {
                $roomid[] = $from->facil;
            }
            $data['fasilitas_room'] = $roomid;



            ////hotel
            $hotel = hotel::find($data->hotel_id);
            $data['hotel'] = $hotel->nama;
            $data['alamat'] = $hotel->alamat;
            $data['lat'] = $hotel->lat;
            $data['long'] = $hotel->long;

            $hotelfacil = hotelfacil::where('hotel_id', $hotel->id)->get();
            $hotelfa = [];
            
            foreach ($hotelfacil as $hf => $hofa) {
                $hotelfa[] = $hofa->facil;
            }
            $data['fasilitas_hotel'] = $hotelfa;
            // $data['hotel'] = $hotel->nama;

            return response()->json(['data' => (['kamar' => $data,])]);
        }
    }
}
