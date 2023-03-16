<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\SettingsFront;

class SettingFront {
    public static function get_setting() {
        $data = SettingsFront::first();
        return $data;
    }
}
