<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\imagehotel;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class mulimage extends Controller
{
    public function mul(Request $req)
    {
        $file = $req->file('image');


        $f = [];
        foreach ($req->file('image')  as $value) {

            $fotoname = time() . '.' . $value->getClientOriginalExtension();
            /////
            ////
            $fotorender = Image::make($value)->resize(600, null, function ($con) {
                $con->aspectRatio();
            });
            /////////
            $file =  Storage::disk('public')->put("detail/hotel/" . $fotoname, (string) $fotorender->encode());

            if ($file) {

                $data = imagehotel::create([

                    'hotel_id' => 1,
                    'detail_image' => "/storage/detail/hotel/" . $fotoname,

                ]);
                $f[] = $data;
            } else {
                return response()->json(['message' => 'failed']);
            }
        }

        // dd($f);

        return response()->json(['image' => $f]);
    }
}
