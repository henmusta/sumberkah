<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;
    protected $table = 'customer';
    protected $fillable = [
      'name',
      'alamat',
      'kontak',
      'telp',
      'keterangan_customer',
      'validasi',
    ];

    public function rute()
    {
      return $this->hasMany(Rute::class,  'customer_id');
    }


}
