<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function bayar()
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->where('status', 'pending')->first();
    
        if ($transaction) {
            // Transaction found, proceed with the view
            return view('bayar', compact('transaction'));
        } else {
            // Handle the case where no pending transaction is found
            // You might redirect or show an appropriate message to the user
            return redirect()->route('home')->with('message', 'No pending transactions found.');
        }
    }
        public function api()
    {
        $newTransaction = Transaction::where('user_id', Auth::user()->id)->where('status', '0')->first();

        if ($newTransaction) {
            // Proceed only if $newTransaction is not null
            $cart = TransactionDetail::where('transaksi_id', $newTransaction->id)->get();
            return json_encode($cart);
        } else {
            // Handle the case where $newTransaction is null
            return json_encode(['error' => 'No transaction found.']);
        }
        
    }
    public function transactionApi()
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->where('status', 'pending')->first();

        return json_encode($transaction) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    
    public function addToCart(Request $request)
    {
        $newTransaction = Transaction::where('user_id', Auth::user()->id)
            ->where('status', '0')
            ->first();

    
        if (empty($newTransaction)) {
            $uuid = Str::uuid();

            $transaction = new Transaction;
            $transaction->user_id = $request->user()->id;
            $transaction->total = 0;
            $transaction->status = 0;
            $transaction->uuid = $uuid;
            $transaction->snap_token = null;
            $transaction->save();
    
            $newTransaction = $transaction;
        }
    
        $newTransactionDetail = TransactionDetail::where('menu', $request->nama)
            ->where('transaksi_id', $newTransaction->id)
            ->first();
    
        if (empty($newTransactionDetail)) {

            $transactionDetail = new TransactionDetail;
            $transactionDetail->transaksi_id = $newTransaction->id;
            $transactionDetail->menu = $request->nama;
            $transactionDetail->harga = $request->harga;
            $transactionDetail->quantity = $request->quantity;
            $transactionDetail->total = $request->harga * $request->quantity;
    
            $transactionDetail->save();
        } else {
            $newTransactionDetail->quantity = $newTransactionDetail->quantity + $request->quantity;
            $newTransactionDetail->total = $newTransactionDetail->quantity * $request->harga;
            $newTransactionDetail->update();
        }
    
        $newTransaction->total = TransactionDetail::where('transaksi_id', $newTransaction->id)->sum('total');
        $newTransaction->update();
    
        return redirect()->route('home');
    }
    
    
    public function checkout(Request $request, Transaction $transaction)
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->where('status', '0')->first();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $transaction->uuid,
                'gross_amount' => $transaction->total,
            ),
            'customer_details' => array(
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ),
            
        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $transaction->snap_token = $snapToken;
        $transaction->status = 'pending';
        $transaction->update();

    }
    public function success(Transaction $transaction)
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->where('status', 'pending')->first();
        $transaction->status= 'success';
        $transaction->update();

        return redirect()->route('home');
    }
    
    public function Admin()
    {
        return view('admin');
    }
    public function apiAdmin()
    {
        $transactions = Transaction::all();
    
        $transactions = $transactions->map(function ($item, $index) {
            $item['index'] = $index + 1;
            return $item;
        });
    
        return response()->json($transactions);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {

    }
}
