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

    /**
     * @param $params ["name" => "value"]形式で値を渡す
     */
    public function filter($params){
        
        $querys = array_map(function ($k, $v) {
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
                return ($v == 1) ? "${k} = ${v}" : "";
            else:
                return "${k} like '%${v}%'";
            endif;
        }, array_keys($params), array_values($params));
        //空要素を削除する
        $querys = array_filter($querys);
        $query_str = "select * from ".  $this->table_name . " where " . join(" and ", $querys);
        //return $query_str;
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
