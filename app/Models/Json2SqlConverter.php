<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Json2SqlConverter extends Model
{
    private $json;
    private $table_name;

    /**
     * $json jsonオブジェクト
     * $table_name テーブル名を指定する
     */
    public function __construct($json, $table_name = "cards"){
        $this->json = $json;
        $this->table_name = $table_name;
        
        DB::transaction($this->connect);
    }

    //マルチ言語系の定数
    const langs = ["deDE", "enUS", "esES", "esMX", "frFR", "itIT", "jaJP", "koKR", "plPL", "ptBR", "ruRU", "thTH", "zhCN", "zhTW"];
    const name = "name";
    const text = "text";
    const flavor = "flavor";

    //sqlに書き込む
    private function connect(){
        $f_artist = ":artist";
        $f_attack = ":attack";
        $f_cardClass = ":cardClass";
        $f_collectible = ":collectible";
        $f_cost = ":cost";
        $f_dbfId = ":dbfId";
        $f_health = ":health";
        $f_id = ":id";
        $f_playRequirements = ":playRequirements";
        $f_playerClass = ":playerClass";
        $f_rarity = ":rarity";
        $f_set = ":set";
        $f_type = ":type";


        //テーブルを作成する
        function create_table(){
            //マルチ言語対応のフィールドのための定義を作成する
            function make_multilang_defines($param_name){
                $results = array_map(function ($x) use ($param_name) {
                    return "${param_name}_${x} TEXT";
                }, langs);
            
                return join("," , $results);
            } 
            $corumn_sql = [
                "artist TEXT",
                "attack INTEGER",
                "cardClass TEXT",
                "collectible INTEGER",
                "cost INTEGER",
                "dbfId INTEGER",
                "health INTEGER",
                "id TEXT primary key",
                "playRequirements TEXT",
                "playerClass TEXT",
                "rarity TEXT",
                "'set' TEXT",
                "type TEXT",
            ];

            $sql_create_table = "create table if not exists". $this->table_name . "("
            . join(",", $corumn_sql) . ","
            . make_multilang_defines("name") . ", "
            . make_multilang_defines("text") . ", "
            . make_multilang_defines("flavor")
            . ")";

            DB::statement($sql_create_table);
        }

        //データを挿入する
        function insert($item){
            //マルチ言語対応フィールドのためのカラムフラグを作成する
            function make_multilang_flags($param_name){
                $results = array_map(function ($x) use ($param_name) {
                    return ":${param_name}_${x}";
                }, langs);
            
                return join("," , $results);
            }
            $params = [
                $f_artist       => isset($item->artist)     ? $item->artist     : "",
                $f_attack       => isset($item->attack)     ? $item->attack     : "",
                $f_cardClass    => isset($item->cardClass)  ? $item->cardClass  : "",
                $f_collectible  => isset($item->collectible)? $item->collectible: "",
                $f_cost         => isset($item->cost)       ? $item->cost       : "",
                $f_dbfId        => isset($item->dbfId)      ? $item->dbfId      : "",
                $f_health       => isset($item->health)     ? $item->health     : "",
                $f_id           => isset($item->id)         ? $item->id         : "",
                $f_playRequirements => isset($item->playRequirements) ? json_encode($item->playRequirements) : "",
                $f_playerClass  => isset($item->playerClass)? $item->playerClass: "",
                $f_rarity       => isset($item->rarity)     ? $item->rarity     : "",
                $f_set          => isset($item->set)        ? $item->set        : "",
                $f_type         => isset($item->type)       ? $item->type       : ""
            ];   
            $params += make_multilang_params(name,   isset($item->name)   ? $item->name   : [] );
            $params += make_multilang_params(flavor, isset($item->flavor) ? $item->flavor : [] );
            $params += make_multilang_params(text,   isset($item->text)   ? $item->text : [] );
            
            $sql_insert = "insert into". $this->$this->table_name  ."values (
                ${f_artist} , ${f_attack}, ${f_cardClass}, ${f_collectible}, ${f_cost}, ${f_dbfId},
                ${f_health}, ${f_id}, ${f_playRequirements}, ${f_playerClass}, ${f_rarity}, ${f_set}, ${f_type},"
                . make_multilang_flags("name") . ", "
                . make_multilang_flags("text") . ", "
                . make_multilang_flags("flavor") .
                ")";
            
            DB::insert($sql_insert, $params);

        }

        create_table();
        foreach($json as $item){
            insert($item);
        }
    }
}
