<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function api()
    {
        $menu = Menu::all();
        $menu = $menu->map(function ($item, $index) {
            $item['index'] = $index + 1;
            return $item;
        });

        return json_encode($menu) ;
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
        // dd($request);
        $image = $request->file('gambar');
        $filename = $request->nama_makanan;
        $path = "assets/".$filename.".jpeg";
        // dd($image);
        Storage::disk('public')->put($path,file_get_contents($image));

        $newMenu = new Menu;
        $newMenu->nama_makanan = $request->input('nama_makanan');
        $newMenu->harga = $request->input('harga');
        $newMenu->detail = $request->input('detail');
        $newMenu->save();

        return redirect()->route('menus');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        // dd($request);
        // dd($menu);
        $image = $request->file('gambar');
        $filename = $request->nama_makanan;
        $path = "assets/".$filename.".jpeg";
        // dd($image);
        if($image){
            Storage::disk('public')->put($path,file_get_contents($image));
        }
        $menu = Menu::where('nama_makanan', $request->input('nama_makanan'))->first();
        // dd($menu);
        $menu->delete();
        $menu = new Menu;
        $menu->nama_makanan = $request->nama_makanan;
        $menu->harga = $request->harga;
        $menu->detail = $request->detail;
        $menu->save();
        // dd($request->nama_makanan);
        return redirect()->route('menus');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menus');
    }
}
