<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\room;
use App\Models\roomfacil;
use App\Models\imagehotel;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class HotelController extends Controller
{


    ///index
    public function index()
    {
        $data = hotel::all();
        foreach ($data as $key => $val) {
            ///fasilitashotel
            $fasilitas = hotelfacil::where('hotel_id', $val->id)->get();
            $arr = [];
            foreach ($fasilitas as $da) {
                $arr[] =  $da->facil;
            }
            $data[$key]['fasilitas'] = $arr;
            ///fasilitashotel

            ///imagehotel
            $imagehoel = imagehotel::where('hotel_id', $val->id)->get();
            $imgh = [];
            foreach ($imagehoel as $val) {
                $imgh[] = $val->detail_image;
            }
            $data[$key]['image_hotel'] = $imgh;
            ///imagehotel
        }
        return response()->json(['data' => $data]);
    }


    ///hoteldetail
    public function hoteldetail($id)
    {
        $data = hotel::find($id);
        if ($data == null) {
            return response()->json([
                'message' => 'succes',
                'data' => []
            ]);
        } else {
            ////imagehotel
            $imagehot = imagehotel::where('hotel_id', $data->id)->get();
            $imghot = [];
            foreach ($imagehot as $imgh) {
                $imghot[] = $imgh->detail_image;
            }
            $data['image_hotel'] = $imghot;
            ////imagehotel


            ///fasilitas hotel
            $fasilitas_hotel = hotelfacil::where('hotel_id', $data->id)->get();
            $ar = [];
            foreach ($fasilitas_hotel as $value) {

                $ar[] = $value->facil;
            }
            $data['fasilitas_hotel'] = $ar;
            ///fasilitas hotel

            //roomhotel
            $room = room::where('hotel_id', $data->id)->get();
            $kamar =  $data['kamar'] = $room;
            foreach ($room as $a => $keyroom) {

                ///imageroom
                // $image_room = imageroom::where('room_id', $keyroom->id)->get();
                // $imgro = [];
                // foreach ($image_room as $val) {
                //     $imgro[] = $val->detail_image;
                // }
                // $kamar[$a]['image_room'] = $imgro;
                ///imageroom


                ///fasilitas room
                $fasilitas_room = roomfacil::where('room_id', $keyroom->id)->get();
                $roomid = [];
                foreach ($fasilitas_room as $fr => $from) {
                    $roomid[] = $from->facil;
                }
                $kamar[$a]['fasilitas_room'] = $roomid;
                ///fasilitas room


            }
            //roomhotel

            return response()->json([
                'message' => 'succes',
                'data' => $data,
            ]);
        }
    }

    ///carihotel
    public function carihotel($alamat)
    {
        $data = hotel::where('alamat', 'like', '%' . $alamat . '%')->get();

        foreach ($data as $key => $val) {
            $fasilitas = hotelfacil::where('hotel_id', $val->id)->get();
            $arr = [];
            foreach ($fasilitas as $da) {
                $arr[] =  $da->facil;
            }
            $data[$key]['fasilitas'] = $arr;
        }
        return response()->json(['data' => $data]);
    }

    ///addhotel
    public function addhotel(Request $req)
    {
        $this->validate($req, [
            'nama' => 'required',
            'alamat' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        if ($req->file('thumbnail') != null) {
            $foto = $req->file('thumbnail');
            $fotoname = time() . '.' . $foto->getClientOriginalExtension();
            /////
            ////
            $fotorender = Image::make($foto)->resize(600, null, function ($con) {
                $con->aspectRatio();
            });
            /////////
            $file =  Storage::disk('public')->put("thumbnail/" . $fotoname, (string) $fotorender->encode());
            // return response()->json([$fotoname]) ;
            if ($file) {
                $finish =  hotel::create([
                    'nama' => $req->nama,
                    'alamat' => $req->alamat,
                    'lat' => $req->lat,
                    'long' => $req->long,
                    'thumbnail' => 'storage/thumbnail/' . $fotoname,
                ]);

                /////addfacil
                $data = json_decode($req->facil);
                $fa = [];
                foreach ($data as $key => $val) {
                    $fa[] = hotelfacil::create([
                        'hotel_id' => $finish->id,
                        'facil' => $val,
                    ]);
                }
                return response()->json([
                    'message' => 'succes',
                    'data' => $finish,
                    'fasilitas' => $fa,
                ]);
            } else {
                return response()->json([
                    'message' => 'failed'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'failed'
            ]);
        }
    }
}
