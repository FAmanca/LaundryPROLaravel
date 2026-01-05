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
}
