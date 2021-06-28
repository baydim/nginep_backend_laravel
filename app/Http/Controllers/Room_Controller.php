<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\hoteltextfacils;
use Illuminate\Http\Request;
use App\Models\room;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\roomfacil;
use App\Models\imageroom;

class Room_Controller extends Controller
{
    public function allRoom()
    {
        $data = room::with('image_room', 'fasilitas',)->get();
        return response()->json(['message' => 'succes', 'data' => $data]);
    }

    public function detailRoom($id)
    {
        // $data = room::where('id', $id)->with('image_room:room_id,detail_image', 'fasilitas:room_id,facil');

        $data = room::find($id);

        if (!$data) {
            return response()->json(['message' => 'data tidak ada', 'data' => $data]);
        } else {

            $data['image_room'] = imageroom::where('room_id', $data->id)->pluck('detail_image');
            $data['fasilitas_room'] = roomfacil::where('room_id', $data->id)->pluck('facil');
            $data['hotel'] = hotel::find($data->hotel_id)->pluck('nama')->first();
            $data['fasilitas_hotel'] = hotelfacil::where('hotel_id', $data->hotel_id)->pluck('facil');
            $data['fasilitas_text_hotel'] = hoteltextfacils::where('hotel_id', $data->hotel_id)->pluck('facil');
            return response()->json(['message' => 'succes', 'data' => $data]);
        }
    }


    public function addRoom(Request $req)
    {
        if ($req->hotel_id == null || $req->nama == null || $req->kasur == null || $req->deskripsi == null || $req->harga == null || $req->fasilitas == null || $req->file('thumbnail') == null || $req->file('details') == null) {
            return response()->json(['message' => 'data belum lengkap']);
        } else {
            $foto = $req->file('thumbnail');
            $fotoname = time() . '.' . $foto->getClientOriginalExtension();
            $fotorender = Image::make($foto)->resize(600, null, function ($con) {
                $con->aspectRatio();
            });
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
                $data = json_decode($req->fasilitas);
                $fasilitas_room = [];
                foreach ($data as $value) {
                    $fasilitas_room[] =  roomfacil::create(['room_id' => $kamar->id, 'facil' => $value]);
                }
                //uploadimages
                $details_room = [];
                foreach ($req->file('details') as $a) {
                    $fotoname = time() . '.' . $a->getClientOriginalExtension();
                    $fotorender = Image::make($a)->resize(600, null, function ($con) {
                        $con->aspectRatio();
                    });
                    $file =  Storage::disk('public')->put("detail/room/" . $fotoname, (string) $fotorender->encode());
                    if ($file) {
                        $details_room[] = imageroom::create([
                            'room_id' => $kamar->id,
                            'detail_image' => "/storage/detail/room/" . $fotoname,
                        ]);
                    } else {
                        return response()->json(['message' => 'gagal upload detail image room']);
                    }
                }
                return response()->json(['message' => 'succes', 'kamar' => $kamar, 'fasilitas' => $fasilitas_room, 'images' => $details_room]);
            } else {
                return response()->json(['message' => 'gagal upload thumbnail']);
            }
        }
    }

    public function deleteroom($id)
    {
        $room = room::find($id);
        if ($room != null) {

            $facilroom = roomfacil::where('room_id', $room->id)->get();
            if ($facilroom == null) {
            } else {
                foreach ($facilroom as $value) {
                    $value->delete();
                }
            }
            $imagrm = imageroom::where('room_id', $room->id)->get();
            if ($imagrm == null) {
            } else {
                foreach ($imagrm as $a) {
                    $a->delete();
                }
            }
            $room->delete();
            return response()->json(['message' => 'succes',]);
        } else {
            return response()->json(['message' => 'failed, not found ',]);
        }
    }
}
