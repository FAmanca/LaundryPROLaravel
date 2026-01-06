<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'customer_id';

    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'customer_id');
    }

    public static function getStatistics()
    {
        $totalLifetime = Transaction::sum('total');
        $totalCustomers = self::count();
        $averageLifetime = $totalCustomers > 0 ? $totalLifetime / $totalCustomers : 0;

        $newCustomers = self::whereDate('created_at', today())->count();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $activeThisMonth = self::whereHas('transactions', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })->get();

        $activeCount = $activeThisMonth->count();

        return [
            'averageLifetime' => $averageLifetime,
            'newCustomers' => $newCustomers,
            'activeCount' => $activeCount,
        ];
    }
}
