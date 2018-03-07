<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Json2SqlConverter;
use Illuminate\Filesystem\Filesystem;

class Json2SqlConvertController extends Controller
{
    public function index(Request $request){
        $fs = new FileSystem();
        $file = $fs->get(public_path("storage/cards.json"));
        $json = json_decode($file);
        Json2SqlConverter::convert($json);
    }
}
