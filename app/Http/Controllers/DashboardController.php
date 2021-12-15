<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderByDesc('trx_date')->limit(5)->get();

        $chart = DB::select('
            SELECT MONTHNAME(trx_date) month, count(*) total FROM transactions
            GROUP BY MONTHNAME(trx_date)
            ORDER BY MONTH(trx_date)
        ');

        // dd($chart);

        $months = [];
        $data = [];

        foreach ($chart as $c) {
            $months[] = $c->month;
            $data[] = $c->total;
        }

        // $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Dec'];
        // $data = [23, 23, 72, 63, 24, 65, 65, 43, 64, 75, 21];

        // dd(json_encode($data));

        return view('admin.dashboard', [
            'transactions' => $transactions,
            'months' => $months,
            'data' => $data,
        ]);
    }
}
