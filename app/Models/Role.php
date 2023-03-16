<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'roles';
    protected $fillable = [
      'name',
      'slug',
      'urut',
      'parent',
    ];

    protected static function boot()
    {
      parent::boot();

      static::creating(function ($roles) {
        $roles->slug = Str::slug($roles->name);
      });

      static::updating(function ($roles) {
        $roles->slug = Str::slug($roles->name);
      });
    }

    public function permissions()
    {
      return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
      return $this->belongsToMany(User::class);
    }


}
