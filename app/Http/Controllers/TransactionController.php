<?php

namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Imports\TransactionImport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function Index()
    {
        $transactions = Transaction::paginate(10);
        return view('admin.transaction.index', ['transactions' => $transactions]);
    }


    public function create()
    {
        return view('admin.transaction.create');
    }

    public function import(Request $request)
    {
       $request->validate([
            'import' => 'required|file|mimes:xlsx,xls',
       ]);

       Excel::import(new TransactionImport(), $request->file('import'));

    //    dd('Berhasil di import');
    }

    public function export()
    {
        return Excel::download(new TransactionExport(), 'transaction.xlsx');
    }
}
