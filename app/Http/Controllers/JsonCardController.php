<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\JsonCard;

class JsonCardController extends Controller
{

    //ex api?name=portal
    //and検索のみ対応 or検索については未対応
    public function index(Request $request){
        $items = JsonCard::get()->items();
        //名前
        if (isset($request->name)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->name) && mb_stripos($x->name->enUS, $request->name) || mb_stripos($x->name->jaJP, $request->name);
            }));
        endif;

        //テキスト
        if (isset($request->text)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->text) && mb_stripos($x->text->enUS, $request->text) || mb_stripos($x->text->jaJP, $request->text);
            }));
        endif;
 
        //フレーバーテキスト
        if (isset($request->flavor)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                 return isset($x->flavor) && mb_stripos($x->flavor->enUS, $request->flavor) || mb_stripos($x->flavor->jaJP, $request->flavor);
            }));
        endif;       
        
        //artist
        if (isset($request->artist)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->artist) && stripos($x->artist, $request->artist);
           }));
        endif;

        //mana
        if (isset($request->cost)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->cost) && ($x->cost == $request->cost);
            }));
        endif;

        //atk
        if (isset($request->attack)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->attack) && ($x->attack == $request->attack);
            }));
        endif;

        //health
        if (isset($request->health)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->health) && ($x->cost == $request->health);
            }));
        endif;

        //rarity (RARE)
        if (isset($request->rarity)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->rarity) 
                    && strcasecmp($x->rarity == $request->rarity) == 0;
            }));
        endif;

        //種類( MINION , SPELL, WEAPON)
        if (isset($request->type)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->type) 
                    && strcasecmp($x->type ,$request->type) == 0;
            }));
        endif;

        //パック(TGT)
        if (isset($request->set)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->set) 
                    && strcasecmp($x->set ,$request->set) == 0;
            }));
        endif;

        //class
        if (isset($request->playerClass)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->playerClass) 
                    && strcasecmp($x->playerClass ,$request->playerClass) == 0;
            }));
        endif;
        
        //上と同じ？
        if (isset($request->cardClass)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->cardClass) 
                    && strcasecmp($x->cardClass ,$request->cardClass) == 0;
            }));
        
        endif;
        
        // id
        if (isset($request->cardClass)):
            $items = response()->json(array_filter($items, function($x) use ($request) {
                return isset($x->cardClass) 
                    && strcasecmp($x->cardClass ,$request->cardClass) == 0;
            }));
        endif;
        return $items;
        //$request->json(JsonCard::get()->items());
    }
}
