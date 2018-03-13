<?php
//sqliteからカードデータを読み込む
//todo: 複数テーブルに対応する

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
// use Illuminate\Filesystem\Filesystem;

class SqlCardReader extends Model
{
    private $table_name;
    private function __construct($table_name){
        // $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->table_name = $table_name;
    }

    // key => value をsql形式に変換する
    public function convertSql($k , $v){
            //複数の言語に対応しているもの
            if ($k === 'name' || $k === 'text' || $k === 'flavor'):
                return "(${k}_enus like '%$v%' or ${k}_jajp like '%$v%')";
            //数値型はそのまま比較する
            elseif ($k === 'cost' || $k === 'attack' || $k === 'health' || $k === 'durability'):
                //ワイルドカード時は何も返さない
                if ($v === '*')
                    return "";
                return "${k} = ${v}";
            //コレクション可能な値は1以外は除去
            elseif ($k === 'collectible'):
                //$vは数値ではない
                return ($v == '1') ? "${k} = ${v}" : "";
            else:
                return "${k} like '%${v}%'";
            endif;
    }

    /**
     * @param $params ["name" => "value"]形式で値を渡す
     */
    public function filter($params){

        $querys = array_map(function ($k,$v){return $this->convertSql($k, $v);} , array_keys($params), array_values($params));
        //空要素を削除する
        $querys = array_filter($querys);
        $query_str = "select * from ".  $this->table_name . " where " . join(" and ", $querys);
        //return $query_str;
        return DB::select($query_str);
    }

    public function filterJsonInnner($json, $join_type='and')
    {
        $result = [];
        $arr - array($json);

        foreach ($arr as $key => $value){
            if ($key === 'or'):
                $result += $this->filterJsonInnner($value, 'or');
            elseif ($key === 'and'):
                $result += $this->filterJsonInnner($value, 'and');
            else:
                $result += $this->convertSql($key, $value);
            endif;
        }
        $result = array_filter($result);
        return '(' . join(" $join_type ", $result) . ')';
    }

    /*
        {
            and:$params1,
            or: $params2,
            and: { or: $params3, and :$params4}
        }
    */
    public function filterJson($json, $root_join_type = 'and'){
        $query_str = "select * from ".  $this->table_name . " where " . $this->filterJsonInnter($json, $root_join_type);
        return DB::select($query_str);

    }

    static $cardReader;
    public static function get($table_name = "cards"){
        if (!isset($cardReader)){
            $cardReader = new SqlCardReader($table_name);
        }
        return $cardReader;
    }
}
