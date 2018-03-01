<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardReader;

class JsonCardController2 extends Controller
{
    public function index(Request $request){
        $cardreader =  CardReader::get();
        $params = [];
        if (isset($request->name)){
            $params['name'] = $request->name;
        }

        return $cardreader->filter($request->all());
        //return "OK";
    }
}
