<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $fillable = ['transaksi_id', 'menu', 'harga', 'quantity', 'total'];

    public function transaction() {
        return $this->belongsTo(Transaction::class,'transaksi_id'); 
    }
}
