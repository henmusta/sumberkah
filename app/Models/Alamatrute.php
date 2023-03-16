<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamatrute extends Model
{
    public $timestamps = false;
    protected $table = 'alamatrute';
    protected $fillable = [
      'name',
      'keterangan',
    ];

    public function rute()
    {
      return $this->hasMany(Rute::class, 'first_rute_id');
    }

    public function rute_last()
    {
      return $this->hasMany(Rute::class, 'last_rute_id');
    }


}
