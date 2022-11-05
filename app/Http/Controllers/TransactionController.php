<?php

namespace App\Http\Controllers;

use App\Exports\MyEvidencesExport;
use App\Models\Comittee;
//use App\Models\Evidence;
//use App\Models\File;
//use App\Models\Proof;
use App\Models\Transaction;
use App\Rules\CheckHoursAndMinutes;
use App\Rules\MaxCharacters;
use App\Rules\MinCharacters;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function list()
    {
        $transactions = Transaction::all();
        $instance = \Instantiation::instance();



        return view('transaction.list',
            ['instance' => $instance, 'transactions' => $transactions]);
    }
}