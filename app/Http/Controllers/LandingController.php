<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function search(Request $request)
    {
        $kode_laundry = $request->input('code');
        Log::info('Searching for laundry with code:', ['kode_laundry' => $kode_laundry]);

        $laundry = Transaction::where('transaction_code', $kode_laundry)
            ->with(['customer', 'parfume', 'user', 'details.service'])
            ->first();

            Log::info('Laundry Search Result:', ['laundry' => $laundry]);

        return response()->json($laundry);

    }
}
