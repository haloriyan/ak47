<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    public function create(Request $request) {
        $saveData = Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price_yearly' => $request->price_yearly,
            'price_monthly' => $request->price_monthly,
            'commission_fee' => $request->commission_fee,
            'max_team_members' => $request->max_team_members,
            'download_report_ability' => $request->download_report_ability,
            'max_file_size' => $request->max_file_size,
        ]);

        return redirect()->route('admin.package')->with([
            'message' => "Berhasil menambahkan package"
        ]);
    }
    public function update(Request $request) {
        $data = Package::where('id', $request->id);
        $package = $data->first();
        $downloadReportAbility = $request->download_report_ability == "1" ? true : false;

        $toUpdate = [
            'name' => $request->name,
            'description' => $request->description,
            'price_yearly' => $request->price_yearly,
            'price_monthly' => $request->price_monthly,
            'commission_fee' => $request->commission_fee,
            'download_report_ability' => $request->download_report_ability,
        ];

        $updateData = $data->update($toUpdate);
        
        return redirect()->route('admin.package')->with([
            'message' => "Berhasil mengubah package"
        ]);
    }
    public function delete(Request $request) {
        $data = Package::where('id', $request->id);
        $package = $data->first();
        $subtitutePackage = Package::where('price_monthly', '<', $package->price_monthly)
        ->orderBy('price_monthly', 'DESC')->first();

        $deleteData = $data->delete();
        $subtituteOrganizerPackage = Organizer::where('package_id', $package->id)->update([
            'package_id' => $subtitutePackage->id,
        ]);

        return redirect()->route('admin.package')->with([
            'message' => "Berhasil menghapus package"
        ]);
    }
}
