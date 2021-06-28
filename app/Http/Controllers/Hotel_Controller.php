<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\hoteltextfacils;
use App\Models\imagehotel;
use App\Models\imageroom;
use App\Models\room;
use App\Models\roomfacil;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Hotel_Controller extends Controller
{
    public function allHotel()
    {
        $data = hotel::with('image_hotel', 'fasilitas', 'rooms')->get();
        return response()->json(['message' => 'succes', 'data' => $data]);
    }

    public function detailHotel($id)
    {
        $data = hotel::find($id);
        if ($data == null) {
            return response()->json(['message' => 'data not found', 'data' => $data]);
        }
        $data['fasilitas_hotel'] = hotelfacil::where('hotel_id', $data->id)->pluck('facil');
        $data['fasilitas_text_hotel'] = hoteltextfacils::where('hotel_id', $data->id)->pluck('facil');
        $data['image_hotel'] = imagehotel::where('hotel_id', $data->id)->pluck('detail_image');
        $kamar = room::where('hotel_id', $data->id)->where('status', 0)->with('fasilitas:room_id,facil', 'image_room:room_id,detail_image')->get();
        $data['kosong'] = $kamar->count();
        $data['kamar'] = $kamar;
        return response()->json(['message' => 'succes',  'data' => $data]);
    }

    public function deletHotel($id)
    {
        $data = hotel::find($id);

        if ($data == null) {
            return response()->json(['message' => 'data not found', 'data' => $data]);
        } else {
            ////delet kamar;
            $kamar = room::where('hotel_id', $data->id)->get();
            if ($kamar == null) {
            } else {
                foreach ($kamar as $value) {
                    ///hapus fasilitas
                    $fasilitas_kamar = roomfacil::where('room_id', $value->id)->get();
                    if ($fasilitas_kamar == null) {
                    } else {
                        foreach ($fasilitas_kamar as  $a) {
                            $a->delete();
                        }
                    }

                    ///hapus image
                    $image_room = imageroom::where('room_id', $value->id)->get();
                    if ($image_room == null) {
                    } else {
                        foreach ($image_room as $b) {
                            $b->delete();
                        }
                    }
                    $value->delete();
                }
            }
            /////delet image hotel;
            $imagehotel = imagehotel::where('hotel_id', $data->id)->get();
            if ($imagehotel == null) {
            } else {
                foreach ($imagehotel as $c) {
                    $c->delete();
                }
            }
            ////delete fasilitas hotel;
            $fasilitas_hotel = hotelfacil::where('hotel_id', $data->id)->get();

            foreach ($fasilitas_hotel as $d) {
                $d->delete();
            }
            $data->delete();
            return response()->json(['message' => 'delete succes']);
        }
    }

    /////addhotel
    public function addHotel(Request $req)
    {
        // $this->validate($req, [
        //     'nama' => 'required',
        //     'alamat' => 'required',
        //     'lat' => 'required',
        //     'long' => 'required',
        // ]);
        if ($req->file('thumbnail') == null || $req->nama == null ||  $req->alamat == null || $req->lat == null || $req->long == null || $req->file('details') == null || $req->faciltext == null || $req->facil == null || $req->deskripsi == null ) {
            return response()->json([
                'message' => 'gagal'
            ]);
        } else {
            ///start render thumbnail
            $foto = $req->file('thumbnail');
            $fotoname = time() . '.' . $foto->getClientOriginalExtension();
            $fotorender = Image::make($foto)->resize(600, null, function ($con) {
                $con->aspectRatio();
            });
            $file =  Storage::disk('public')->put("thumbnail/hotel/" . $fotoname, (string) $fotorender->encode());
            // return response()->json([$fotoname]) ;
            if ($file) {
                ////input hotel
                $finish =  hotel::create([
                    'nama' => $req->nama,
                    'alamat' => $req->alamat,
                    'deskripsi' => $req->deskripsi,
                    'lat' => $req->lat,
                    'long' => $req->long,
                    'thumbnail' => '/storage/thumbnail/hotel/' . $fotoname,
                ]);
                ///////input hotel

                /////addfacil
                $data = json_decode($req->facil);
                $fa = [];
                foreach ($data as $val) {
                    $fa[] = hotelfacil::create([
                        'hotel_id' => $finish->id,
                        'facil' => $val,
                    ]);
                }
                /////addfacil

                ////addfaciltext

                $fatext = json_decode($req->faciltext);
                $fat = [];
                foreach ($fatext as $vall) {
                    $fat[] = hoteltextfacils::create([
                        'hotel_id' => $finish->id,
                        'facil' => $vall,
                    ]);
                }
                // dd($req->faciltext );

                ////addfaciltext

                ////upload image detail hotel
                $f = [];
                foreach ($req->file('details')  as $value) {
                    $fotoname = time() . '.' . $value->getClientOriginalExtension();
                    $fotorender = Image::make($value)->resize(600, null, function ($con) {
                        $con->aspectRatio();
                    });
                    /////////
                    $file =  Storage::disk('public')->put("detail/hotel/" . $fotoname, (string) $fotorender->encode());
                    if ($file) {
                        $data = imagehotel::create([
                            'hotel_id' => $finish->id,
                            'detail_image' => "/storage/detail/hotel/" . $fotoname,
                        ]);
                        $f[] = $data;
                    } else {
                        return response()->json(['message' => 'gagal upload foto detail hotel']);
                    }
                }
                ////upload image detail hotel

                return response()->json([
                    'message' => 'succes',
                    'data' => $finish,
                    'fasilitas' => $fa,
                    'fasilitas_text' => $fat,
                    'image_hotel' => $f,
                ]);
            } else {
                return response()->json([
                    'message' => 'gagal, data tidak lengkap'
                ]);
            }
        }
    }
}
