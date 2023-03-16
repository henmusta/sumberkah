<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muatan extends Model
{
    public $timestamps = false;
    protected $table = 'muatan';
    protected $fillable = [
      'name',
      'keterangan'
    ];

    public function rute()
    {
      return $this->hasMany(Rute::class);
    }



}
