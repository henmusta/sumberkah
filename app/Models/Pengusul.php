<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengusul extends Model
{
    use HasFactory;
    protected $table = 'pengusul';
    protected $fillable = [
     'name',
     'deskripsi',
   ];



}
