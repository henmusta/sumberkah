<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilRincian extends Model
{
    public $timestamps = false;
    protected $table = 'mobilrincian';
    protected $fillable = [
      'jenismobil_id',
      'merkmobil_id',
      'tipemobil_id',
      'dump',
      'validasi',
    ];

    public function jenismobil()
    {
      return $this->belongsTo(Jenismobil::class, 'jenismobil_id');
    }
    public function merkmobil()
    {
      return $this->belongsTo(Merkmobil::class, 'merkmobil_id');
    }
    public function tipemobil()
    {
      return $this->belongsTo(Tipemobil::class, 'tipemobil_id');
    }

}
