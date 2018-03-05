<?php
//sqliteからカードデータを読み込む

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Filesystem\Filesystem;

class CardReader extends Model
{
    private $path;
    private $pdo;
    private function __construct(){
        $this->path = public_path('storage/cards.sqlite');
        $this->pdo = new \PDO("sqlite:" . $this->path);
    }

    /**
     * @param $params ["name" => "value"]形式で値を渡す
     */
    public function filter($params){
        
        $querys = array_map(function ($k, $v) {
            //複数の言語に対応しているもの
            if ($k === 'name' || $k === 'text' || $k === 'flavor'):
                return "where (${k}_enus like '%$v%' or ${k}_jajp like '%$v%')";            
            else:
                return "where ${k} like '%${v}%'";
            endif;
        }, array_keys($params), array_values($params));

        $query_str = "select * from cards " . join(" and ", $querys);
        // return $query_str;
        return $this->pdo->query($query_str)->fetchAll(\PDO::FETCH_ASSOC);
    }

    static $cardReader;
    public static function get(){
        if (!isset($cardReader)){
            $cardReader = new CardReader();
        }
        return $cardReader;
    }
}
