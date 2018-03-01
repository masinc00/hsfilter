<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Storage;

use App\Models\JsonCard;

class MainController extends Controller
{
    //
    public function index(Request $request){
        
        $jsonCard = JsonCard::get();
        $items = $jsonCard->items();

        // $data = ['items' => $items];
        $data = ['items' => []];
        return view('main/index', $data);
    }
}
