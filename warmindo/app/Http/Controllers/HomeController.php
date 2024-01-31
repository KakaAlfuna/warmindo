<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function countCart()
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->where('status', '0')->first();
        // dd($transaction);
        if($transaction){
            $cart = TransactionDetail::where('transaksi_id', $transaction->id)->count();
        }else{
            $cart = 0;
        }

        return json_encode($cart);
    }
}
