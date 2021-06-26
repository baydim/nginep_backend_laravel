<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\room;
use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\roomfacil;
use App\Models\imageroom;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


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
            $hotelfacil = hotelfacil::where('hotel_id', $hotel->id)->get();
            $hotelfa = [];

            foreach ($hotelfacil as $hofa) {
                $hotelfa[] = $hofa->facil;
            }
            $data['fasilitas_hotel'] = $hotelfa;
            ////hotel

            return response()->json(['data' => (['kamar' => $data,])]);
        }
    }



    public function addroom(Request $req)
    {
        $this->validate($req, [
            'hotel_id' => 'required',
            'nama' => 'required',
            'kasur' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'fasilitas' => 'required',


        ]);

        if ($req->hotel_id == null || $req->nama == null || $req->kasur == null || $req->deskripsi == null || $req->harga == null || $req->fasilitas == null || $req->file('thumbnail') == null) {
            return response()->json(['message' => 'failed']);
        } else {

            $foto = $req->file('thumbnail');
            $fotoname = time() . '.' . $foto->getClientOriginalExtension();
            /////
            ////
            $fotorender = Image::make($foto)->resize(600, null, function ($con) {
                $con->aspectRatio();
            });
            /////////
            $file =  Storage::disk('public')->put("thumbnail/room/" . $fotoname, (string) $fotorender->encode());


            if ($file) {

                $hotel = hotel::find($req->hotel_id);


                $kamar = room::create(
                    [
                        'hotel_id' => $req->hotel_id,
                        'nama' => $req->nama,
                        'kasur' => $req->kasur,
                        'status' => 0,
                        'deskripsi' => $req->deskripsi,
                        'harga' => $req->harga,
                        'thumbnail' => '/storage/thumbnail/room/' . $fotoname,
                        'alamat' => $hotel->alamat,
                        'lat' => $hotel->lat,
                        'long' => $hotel->long,
                    ]
                );
                ////addkamar

                //////add fasilitas
                $data = json_decode($req->fasilitas);
                $fasilitas_room = [];
                foreach ($data as $value) {
                    $fasilitas_room[] =  roomfacil::create(['room_id' => $kamar->id, 'facil' => $value]);
                }
                //////add fasilitas

                return response()->json(['message' => 'succes', 'kamar' => $kamar, 'fasilitas' => $fasilitas_room]);
            } else {
                return response()->json(['message' => 'failed']);
            }
            ////addkamar

        }
    }

    public function deleteroom($id)
    {
        $room = room::find($id);

        if ($room != null) {
            $facilroom = roomfacil::where('room_id', $room->id)->get();

            if ($facilroom != null) {
                foreach ($facilroom as $value) {
                    $value->delete();
                }
            }

            $room->delete();
            return response()->json(['message' => 'succes',]);
        } else {
            return response()->json(['message' => 'failed, not found ',]);
        }
    }
}
