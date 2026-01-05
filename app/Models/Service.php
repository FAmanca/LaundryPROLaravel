<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Service extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    protected $primaryKey = 'service_id';

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'service_id', 'service_id');
    }


}
