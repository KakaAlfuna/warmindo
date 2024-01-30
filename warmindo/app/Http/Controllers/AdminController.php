<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $banyakMenu = Menu::count();
        $banyakMinuman = Drink::count();
        $banyakUser = User::count();
        $banyakTransaksi = Transaction::count();

        $label_bar = ['Transaction'];
        $data_bar = [];

        foreach ($label_bar as $key => $value) {
            $data_bar[$key]['label'] = $label_bar[$key];
            $data_bar[$key]['backgroundColor'] =  $key == 0 ? 'rgba(60,141,188,0,9)' : 'rgba(210,214,222,1)';
            $data_month = [];

            foreach (range(1,12) as $month) {
                if ($key == 0){
                    $data_month[] = Transaction::where('status', 'success')->whereMonth('created_at', $month)->count();
                }
            }
            $data_bar[$key]['data'] = $data_month;
        }
        return view('admin', compact('banyakMenu', 'banyakMinuman', 'banyakUser', 'banyakTransaksi', 'data_bar'));
    }

    public function transaksi()
    {
        return view('transactions');
    }
    public function menu()
    {
        return view('menus');
    }
    public function minuman()
    {
        return view('drinks');
    }
}
