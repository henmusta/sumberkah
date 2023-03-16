<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable = [
     'name',
     'icon',
     'sidebar_logo',
     'favicon',
     'layout',
     'layout_mode',
     'layout_position',
     'layout_width',
     'topbar_color',
     'sidebar_size',
     'sidebar_color'

   ];
}
