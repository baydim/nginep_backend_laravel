<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\hotelfacil;
use App\Models\room;
use App\Models\roomfacil;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


use function PHPUnit\Framework\isNull;

class HotelController extends Controller
{


    ///index
    public function index()
    {
        $data = hotel::all();
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


            ///fasilitas hotel
            $fasilitas_hotel = hotelfacil::where('hotel_id', $data->id)->get();
            $ar = [];
            foreach ($fasilitas_hotel as $value) {

                $ar[] = $value->facil;
            }
            $data['fasilitas_hotel'] = $ar;

            //roomhotel
            $room = room::where('hotel_id', $data->id)->get();
            $kamar =  $data['kamar'] = $room;
            foreach ($room as $a => $keyroom) {

                $fasilitas_room = roomfacil::where('room_id', $keyroom->id)->get();
                // $kamar[$a]['fasilitas_room'] = $fasilitas_room;
                $roomid = [];
                foreach ($fasilitas_room as $fr => $from) {
                    $roomid[] = $from->facil;
                }
                $kamar[$a]['fasilitas_room'] = $roomid;
            }

            return response()->json([
                'message' => 'succes',
                'data' => $data,
            ]);
        }
    }

    ///carihotel
    public function carihotel($alamat)
    {
        $data = hotel::where('alamat', 'like', '%' . $alamat . '%')->with('fasilitas:facil,hotel_id')->get();
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
