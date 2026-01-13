<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'transaction_id';
    protected $guarded = [];

    public function details() {
        return $this->hasMany(DetailTransaction::class, 'transaction_id', 'transaction_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id')->withTrashed();;
    }

    public function parfume() {
        return $this->belongsTo(Parfume::class, 'parfume_id', 'parfume_id')->withTrashed();;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'transaction_id', 'transaction_id');
    }

    public function getAmountPaidAttribute()
    {
        return $this->payments()->where('status', 'success')->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total - $this->getAmountPaidAttribute();
    }

    public function getLaundryStatusLabelAttribute()
    {
        return match ($this->laundry_status) {
            'Pending' => 'Pesanan Diterima',
            'Process' => 'Sedang Diproses',
            'Completed' => 'Selesai Dicuci, Menunggu Diambil',
            'Picked Up' => 'Sudah Diambil, Pesanan Selesai',
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            'Paid' => 'Lunas',
            'Partial' => 'DP',
            'Unpaid' => 'Belum Lunas',
        };
    }
}
