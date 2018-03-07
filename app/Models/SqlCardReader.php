<?php
//sqliteからカードデータを読み込む
//todo: 複数テーブルに対応する

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
// use Illuminate\Filesystem\Filesystem;

class SqlCardReader extends Model
{
    private function __construct(){
        // $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param $params ["name" => "value"]形式で値を渡す
     */
    public function filter($params){
        
        $querys = array_map(function ($k, $v) {
            //複数の言語に対応しているもの
            if ($k === 'name' || $k === 'text' || $k === 'flavor'):
                return "(${k}_enus like '%$v%' or ${k}_jajp like '%$v%')";
            elseif ($k === 'cost' || $k === 'attack' || $k === 'health'):
                return "${k} = ${v}";            
            else:
                return "${k} like '%${v}%'";
            endif;
        }, array_keys($params), array_values($params));
        
        $query_str = "select * from cards where " . join(" and ", $querys);
        // return $query_str;
        return DB::select($query_str);
    }

    static $cardReader;
    public static function get(){
        if (!isset($cardReader)){
            $cardReader = new CardReader();
        }
        return $cardReader;
    }
}
