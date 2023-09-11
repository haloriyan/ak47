<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TopicController extends Controller
{
    public function create(Request $request) {
        $icon = $request->file('icon');
        $iconFileName = $icon->getClientOriginalName();

        $saveData = Topic::create([
            'name' => $request->name,
            'icon' => $iconFileName,
            'priority' => 0,
        ]);

        $icon->storeAs('public/topic_icons', $iconFileName);

        return redirect()->route('admin.topic')->with([
            'message' => "Berhasil menambahkan topik"
        ]);
    }
    public function delete(Request $request) {
        $data = Topic::where('id', $request->id);
        $topic = $data->first();

        $deleteData = $data->delete();
        $deleteIcon = Storage::delete('public/topic_icons/' . $topic->icon);

        return redirect()->route('admin.topic')->with([
            'message' => "Berhasil menghapus topik " . $topic->name
        ]);
    }
    public function update(Request $request) {
        $data = Topic::where('id', $request->id);
        $topic = $data->first();

        $toUpdate = ['name' => $request->name];
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconFileName = $icon->getClientOriginalName();
            $deleteIcon = Storage::delete('public/topic_icons/' . $topic->icon);
            $icon->storeAs('public/topic_icons', $iconFileName);
            $toUpdate['icon'] = $iconFileName;
        }

        $updateData = $data->update($toUpdate);

        return redirect()->route('admin.topic')->with([
            'message' => "Berhasil mengubah topik " . $topic->name
        ]);
    }
}
