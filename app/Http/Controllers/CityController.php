<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function create(Request $request) {
        $cover = $request->file('cover');
        $coverFileName = $cover->getClientOriginalName();
        $cover->storeAs('public/city_covers', $coverFileName);

        $saveData = City::create([
            'name' => $request->name,
            'cover' => $coverFileName,
            'priority' => 0
        ]);

        return redirect()->route('admin.city')->with(['message' => "Berhasil menambahkan kota"]);
    }
    public function update(Request $request) {
        $data = City::where('id', $request->id);
        $city = $data->first();
        $toUpdate = ['name' => $request->name];

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverFileName = $cover->getClientOriginalName();
            $toUpdate['cover'] = $coverFileName;
            $deleteOldCover = Storage::delete('public/city_covers/' . $city->cover);
            $cover->storeAs('public/city_covers', $coverFileName);
        }

        $updateData = $data->update($toUpdate);

        return redirect()->route('admin.city')->with(['message' => "Berhasil mengubah kota " . $city->name]);
    }
    public function delete(Request $request) {
        $data = City::where('id', $request->id);
        $city = $data->first();

        $deleteData = $data->delete();
        $deleteCover = Storage::delete('public/city_covers/' . $city->cover);

        return redirect()->route('admin.city')->with(['message' => "Berhasil menghapus kota " . $city->name]);
    }
}
