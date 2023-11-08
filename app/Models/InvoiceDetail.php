<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    public $timestamps = false;
    protected $table = 'invoice_detail_custom';
    protected $fillable = [
      'invoice_id',
      'keterangan',
      'nominal',
      'created_by',
      'updated_by',
    ];

    public function invoice()
    {
      return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function createdby()
    {
      return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedby()
    {
      return $this->belongsTo(User::class, 'updated_by');
    }

}
