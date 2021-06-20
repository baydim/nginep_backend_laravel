<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\imagehotel;


class mulimage extends Controller
{
    public function mul(Request $req)
    {
        $file = $req->image->getClientOriginalName();


        // $f = [];
        // foreach ($req->file('image')  as $value) {
        //  $f[] = $value->getClientOriginalName() ; 
        // }

        // dd($f);
        
        return response()->json(['image' => $file]);
    }
}
