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
        return view('menu.index');
    }

    public function api()
    {
        $menus = Menu::all()->map(function ($item, $index) {
            $item['index'] = $index + 1;
            return $item;
        });

        return json_encode($menus);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_makanan' => 'required|string',
            'harga' => 'required|numeric',
            'detail' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('gambar');
        $filename = $request->nama_makanan;
        $path = "assets/" . $filename . ".jpeg";

        Storage::disk('public')->put($path, file_get_contents($image));

        $newMenu = new Menu;
        $newMenu->nama_makanan = $request->input('nama_makanan');
        $newMenu->harga = $request->input('harga');
        $newMenu->detail = $request->input('detail');
        $newMenu->save();

        return redirect()->route('menus');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_makanan' => 'required|string',
            'harga' => 'required|numeric',
            'detail' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $menu = Menu::findOrFail($id);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $filename = $request->nama_makanan;
            $path = "assets/" . $filename . ".jpeg";

            Storage::disk('public')->put($path, file_get_contents($image));
        }

        $menu->update([
            'nama_makanan' => $request->input('nama_makanan'),
            'harga' => $request->input('harga'),
            'detail' => $request->input('detail'),
        ]);

        return redirect()->route('menus');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
    }
}
