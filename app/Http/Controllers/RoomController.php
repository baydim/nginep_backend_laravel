<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\room;
use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\roomfacil;
use App\Models\imageroom;

class RoomController extends Controller
{
    public function detail($id)
    {
        $data = room::find($id);
        if ($data == null) {
            return response()->json(['data' => $data]);
        } else {
            /////imageroom
            $imageroom = imageroom::where('room_id', $data->id)->get();
            $imgr = [];
            foreach ($imageroom as $value) {
                $imgr[] =  $value->detail_image;
            }
            $data['image_room'] = $imgr;
            /////imageroom

            /////facilkamar
            $fasilitas_room = roomfacil::where('room_id', $data->id)->get();
            $roomid = [];
            foreach ($fasilitas_room as $fr => $from) {
                $roomid[] = $from->facil;
            }
            $data['fasilitas_room'] = $roomid;
            /////facilkamar

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
            ////hotel

            return response()->json(['data' => (['kamar' => $data,])]);
        }
    }



    public function addroom(Request $req)
    {

        if ($req->hotel_id == null || $req->nama == null || $req->kasur == null || $req->deskripsi == null || $req->harga == null || $req->fasilitas == null) {
            return response()->json(['message' => 'failed']);
        } else {

            ////addkamar
            $kamar = room::create(
                [
                    'hotel_id' => $req->hotel_id,
                    'nama' => $req->nama,
                    'kasur' => $req->kasur,
                    'status' => 0,
                    'deskripsi' => $req->deskripsi,
                    'harga' => $req->harga,
                ]
            );
            ////addkamar

            //////add fasilitas
            $data = json_decode($req->fasilitas);
            $fasilitas_room = [];
            foreach ($data as $key => $value) {
                $fasilitas_room[] =  roomfacil::create(['room_id' => $kamar->id, 'facil' => $value]);
            }
            //////add fasilitas

            return response()->json(['message' => 'succes', 'kamar' => $kamar, 'fasilitas' => $fasilitas_room]);
        }
    }
}
