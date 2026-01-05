<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $primaryKey = 'detail_transaction_id';
    protected $guarded = [];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id')->withTrashed();;
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'service_id')->withTrashed();;
    }
}
