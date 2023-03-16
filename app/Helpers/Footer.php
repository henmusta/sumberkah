<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Legislasi;
use App\Models\Pages;
use App\Models\TahapanLegislasi;

class Footer {
    public static function get_footer() {
        $legislasi = Legislasi::with('pengusul', 'tahapan');
        $About = Pages::where('name', 'About')->first();
        $tahapan = TahapanLegislasi::withCount('legislasi')->get();
        $data = [
          'legislasi' =>  $legislasi->get(),
          'last_legislasi' => $legislasi->orderBy('id', 'DESC')->limit(3)->get(),
          'tahapan' => $tahapan,
          'about' =>  $About
        ];
        return $data;

    }
}
