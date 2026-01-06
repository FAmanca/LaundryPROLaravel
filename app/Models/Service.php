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

    public static function getStatistics()
    {
        $total_services = self::count();
        $popular_service = self::withCount('detailTransactions')
            ->orderBy('detail_transactions_count', 'desc')
            ->first();
        $avg_service_price = round(self::avg('price'));
        $lowest_service = self::orderBy('price', 'asc')->first();

        return [
            'total_services' => $total_services,
            'popular_service' => $popular_service,
            'avg_service_price' => $avg_service_price,
            'lowest_service' => $lowest_service,
        ];
    }
}

