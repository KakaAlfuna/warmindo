<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable =['user_id', 'status', 'total', 'snap_token', 'uuid'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function transaksiDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaksi_id');
    }
}
