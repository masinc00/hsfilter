<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Storage;

class MainController extends Controller
{
    //
    public function index(Request $request){

        // $data = ['items' => $items];
        $data = ['items' => []];
        return view('main/index', $data);
    }
}
