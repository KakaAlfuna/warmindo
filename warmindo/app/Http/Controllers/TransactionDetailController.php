<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'cart' => 'required|array',
            'cart.*.menu' => 'required',     // Update field name
            'cart.*.harga' => 'required|numeric',    // Update field name
            'cart.*.quantity' => 'required|integer',
        ]);
        
    
        $transaksiId = $request->input('transaksi_id');
        $cartItems = $request->input('cart');
    
        foreach ($cartItems as $cartItem) {
            TransactionDetail::create([
                'transaksi_id' => $transaksiId,
                'menu' => $cartItem['menu'],
                'harga' => $cartItem['harga'],
                'quantity' => $cartItem['quantity'],
            ]);
        }
    
        return redirect()->route('home')->with('success', 'Transaction details stored successfully');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transactionDetail = TransactionDetail::findOrFail($id);
        $transactionDetail->delete();
    }
}
