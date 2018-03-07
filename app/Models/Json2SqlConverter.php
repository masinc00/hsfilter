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
    private function __construct($json, $table_name = "cards"){
        $this->json = $json;
        $this->table_name = $table_name;
        
        DB::transaction(function () { $this->connect(); });
    }

    public static function convert($json, $table_name = "cards"){
        new Json2SqlConverter($json, $table_name);
    }

    //マルチ言語系の定数
    const langs = ["deDE", "enUS", "esES", "esMX", "frFR", "itIT", "jaJP", "koKR", "plPL", "ptBR", "ruRU", "thTH", "zhCN", "zhTW"];
    const name = "name";
    const text = "text";
    const flavor = "flavor";

    //sqlに書き込む処理
    private function connect(){
        //$table_name = $this->table_name;

        $this->create_table();
        foreach($this->json as $item){
            $this->insert($item);
        }
    }


    //テーブルを作成する
    function create_table() {
        //マルチ言語対応のフィールドのための定義を作成する
        function make_multilang_defines($param_name){
            $results = array_map(function ($x) use ($param_name) {
                return "${param_name}_${x} TEXT";
            }, Json2SqlConverter::langs);
        
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
        // $table_name = $this->table_name;
        $sql_create_table = "create table if not exists ". $this->table_name . "("
        . join(",", $corumn_sql) . ","
        . make_multilang_defines("name") . ", "
        . make_multilang_defines("text") . ", "
        . make_multilang_defines("flavor")
        . ")";

        DB::statement($sql_create_table);
    }

    //マルチ言語対応フィールドのためのカラムフラグを作成する
    function make_multilang_flags($param_name){
        $results = array_map(function ($x) use ($param_name) {
            return ":${param_name}_${x}";
        }, Json2SqlConverter::langs);
    
        return join("," , $results);
    }

    //多言語用のパラメータを作成する
    function make_multilang_params($param_name, $params){
        //$result = [];
        $result["${param_name}_enUS"] = isset($params->enUS) ? $params->enUS : ""; 
        $result["${param_name}_jaJP"] = isset($params->jaJP) ? $params->jaJP : "";
        $result["${param_name}_deDE"] = isset($params->deDE) ? $params->deDE : "";
        $result["${param_name}_esES"] = isset($params->esES) ? $params->esES : "";
        $result["${param_name}_esMX"] = isset($params->esMX) ? $params->esMX : "";
        $result["${param_name}_frFR"] = isset($params->frFR) ? $params->frFR : "";
        $result["${param_name}_itIT"] = isset($params->itIT) ? $params->itIT : "";
        $result["${param_name}_koKR"] = isset($params->koKR) ? $params->koKR : "";
        $result["${param_name}_plPL"] = isset($params->plPL) ? $params->plPL : "";
        $result["${param_name}_ptBR"] = isset($params->ptBR) ? $params->ptBR : "";
        $result["${param_name}_ruRU"] = isset($params->ruRU) ? $params->ruRU : "";
        $result["${param_name}_thTH"] = isset($params->thTH) ? $params->thTH : "";
        $result["${param_name}_zhCN"] = isset($params->zhCN) ? $params->zhCN : "";
        $result["${param_name}_zhTW"] = isset($params->zhTW) ? $params->zhTW : "";
        return array_filter($result);
    }

    //insert時のフィールドパラメーター
    const f_artist = ":artist";
    const f_attack = ":attack";
    const f_cardClass = ":cardClass";
    const f_collectible = ":collectible";
    const f_cost = ":cost";
    const f_dbfId = ":dbfId";
    const f_health = ":health";
    const f_id = ":id";
    const f_playRequirements = ":playRequirements";
    const f_playerClass = ":playerClass";
    const f_rarity = ":rarity";
    const f_set = ":set";
    const f_type = ":type";

    const base_fields = [self::f_artist, self::f_attack, self::f_cardClass, self::f_collectible, self::f_cost, self::f_dbfId, 
            self::f_health, self::f_id, self::f_playRequirements, self::f_playerClass, self::f_rarity, self::f_set, self::f_type];

    //個別のデータを挿入する
    function insert($item){
        $params = [
            self::f_artist       => isset($item->artist)     ? $item->artist     : "",
            self::f_attack       => isset($item->attack)     ? $item->attack     : "",
            self::f_cardClass    => isset($item->cardClass)  ? $item->cardClass  : "",
            self::f_collectible  => isset($item->collectible)? $item->collectible: "",
            self::f_cost         => isset($item->cost)       ? $item->cost       : "",
            self::f_dbfId        => isset($item->dbfId)      ? $item->dbfId      : "",
            self::f_health       => isset($item->health)     ? $item->health     : "",
            self::f_id           => isset($item->id)         ? $item->id         : "",
            self::f_playRequirements => isset($item->playRequirements) ? json_encode($item->playRequirements) : "",
            self::f_playerClass  => isset($item->playerClass)? $item->playerClass: "",
            self::f_rarity       => isset($item->rarity)     ? $item->rarity     : "",
            self::f_set          => isset($item->set)        ? $item->set        : "",
            self::f_type         => isset($item->type)       ? $item->type       : ""
        ];   
        $params += $this->make_multilang_params(Json2SqlConverter::name,   isset($item->name)   ? $item->name   : [] );
        $params += $this->make_multilang_params(Json2SqlConverter::flavor, isset($item->flavor) ? $item->flavor : [] );
        $params += $this->make_multilang_params(Json2SqlConverter::text,   isset($item->text)   ? $item->text : [] );
        
        $sql_insert = "insert into ". ($this->table_name)  ." values(" .
            join(",", self::base_fields ) . ","
            . $this->make_multilang_flags("name") . ", "
            . $this->make_multilang_flags("text") . ", "
            . $this->make_multilang_flags("flavor") .
            ")";
        
        DB::insert($sql_insert, $params);

    }
}
