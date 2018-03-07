<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SqlCardReader;

class JsonCardController2 extends Controller
{
    public function index(Request $request){
        $cardreader =  SqlCardReader::get();
        $params = [];
        if (isset($request->name)){
            $params['name'] = $request->name;
        }

        return $cardreader->filter($request->all());
        //return "OK";
    }
}
