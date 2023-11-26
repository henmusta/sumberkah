<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use App\Models\Mobil;
use App\Models\Driver;

trait UpdateSJ {
    public function update_sj() {
        $feild = ['driver', 'mobil'];
        foreach($feild as $val){
        $query = DB::statement("UPDATE
            ".$val."
            INNER JOIN (SELECT
            ".$val.".`id` AS id,
            IF(SUM(IF(status_joborder = '1', 0, 1)) > '0', '1', '0') AS cek_st
            FROM
            ".$val."
            INNER JOIN joborder
            ON joborder.`".$val."_id` =  ".$val.".`id`
            GROUP BY  ".$val.".`id`) child
            ON  ".$val.".id = child.id
            SET  ".$val.".`status_jalan` = child.cek_st");
        }
    }
}
