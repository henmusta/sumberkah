<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Settings;

class Setting {
    public static function get_setting() {
        $data = Settings::first();
        return $data;
    }
}
