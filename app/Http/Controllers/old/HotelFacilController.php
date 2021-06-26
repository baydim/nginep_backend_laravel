<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hotelfacil;

class HotelFacilController extends Controller
{
    public function facil($hotel_id)
    {
        $data = hotelfacil::where('hotel_id', $hotel_id)->get();
        return response()->json(['message' => 'succes', 'data' => $data]);
    }

    public function deletfacil($id_facil)
    {
        $data = hotelfacil::find($id_facil);

        // return response()->json(['data' => $data]);

        if ($data == null) {
            return response()->json(['message' => 'failed']);
        } else {
            $d = $data->delete();
            return response()->json(['message' => 'succes', 'delete' => $d]);
        }
    }
}
