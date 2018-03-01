<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;

class JsonCard extends Model
{
    private $path;
    private $json;
    //しんぐるとん
    private function __construct(){
        $fs = new Filesystem();
        $this->path = public_path('storage/cards/all/cards.collectible.json');
        $this->json = json_decode($fs->get($this->path));
               
    }

    public function getNamesEnglish(){
        return array_map(function ($x) {return $x->name->enUS; }, $this->json);
    }

    public function getNamesJapanese(){
        return array_map(function ($x) {return $x->name->jaJP; }, $this->json);
    }

    //enUS
    public function getData(string $name){
        foreach ($json as $item){
            if ($item->name->enUS === $name):
                return $item;
            endif;
        }
    }

    public function items(){
        return $this->json;
    }

    private static $jsonCard;

    public static function get(){
        if (!isset( $jsonCard)){
            $jsonCard = new JsonCard();
        }
        return $jsonCard;
    }
}
