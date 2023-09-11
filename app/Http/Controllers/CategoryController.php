<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function create(Request $request) {
        $icon = $request->file('icon');
        $iconFileName = $icon->getClientOriginalName();
        $cover = $request->file('cover');
        $coverFileName = $cover->getClientOriginalName();

        $saveData = Category::create([
            'name' => $request->name,
            'color' => "#3498db",
            'icon' => $iconFileName,
            'cover' => $coverFileName,
            'priority' => 0,
        ]);

        $icon->storeAs('public/category_icons', $iconFileName);
        $cover->storeAs('public/category_covers', $coverFileName);

        return redirect()->route('admin.category')->with([
            'message' => "Berhasil menambahkan kategori baru"
        ]);
    }
    public function update(Request $request) {
        $data = Category::where('id', $request->id);
        $category = $data->first();

        $toUpdate = ['name' => $request->name];

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconFileName = $icon->getClientOriginalName();
            $toUpdate['icon'] = $iconFileName;
            $deleteOld = Storage::delete('public/category_icons/' . $category->icon);
            $icon->storeAs('public/category_icons', $iconFileName);
        }
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverFileName = $cover->getClientOriginalName();
            $toUpdate['cover'] = $coverFileName;
            $deleteOld = Storage::delete('public/category_covers/' . $category->cover);
            $cover->storeAs('public/category_covers', $coverFileName);
        }

        $updateData = $data->update($toUpdate);

        return redirect()->route('admin.category')->with([
            'message' => "Berhasil mengubah kategori " . $category->name,
        ]);
    }
    public function delete(Request $request) {
        $data = Category::where('id', $request->id);
        $category = $data->first();

        $deleteData = $data->delete();
        $deleteCover = Storage::delete('public/category_covers/' . $category->cover);
        $deleteIcon = Storage::delete('public/category_icons/' . $category->icon);

        return redirect()->route('admin.category')->with([
            'message' => "Berhasil menghapus kategori " . $category->name,
        ]);
    }
    public function priority($id, $action) {
        $data = Category::where('id', $id);
        $category = $data->first();

        if ($action == "increase") {
            $data->increment('priority');
        } else {
            $data->decrement('priority');
        }

        return redirect()->route('admin.category');
    }
}
