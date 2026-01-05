<?php

namespace App\Http\Controllers;

use App\Models\Parfume;
use Illuminate\Http\Request;
use App\Exports\ParfumeExport;
use App\Imports\ParfumeImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ActivityLogController;

class ParfumeAdminController extends Controller
{
    public function parfume() {
        $parfumes = Parfume::paginate(8);
        return view('admin.parfume',[
            'parfumes' => $parfumes
        ]);
    }

    public function storeParfume(Request $request) {
        $rules = [
            'parfume_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', implode("\n", $validator->errors()->all()));
        }

        $data = $request->only(['parfume_name','description']);

        try {
            Parfume::create($data);
            ActivityLogController::log('Create', 'Parfume Baru Di Tambahkan : ' . $data['parfume_name'], auth()->user()->user_id);

            return redirect()->route('admin.parfumes.index')
                ->with('success', 'parfume added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't add the Parfume. Please try again.")
                ->withInput();
        }
    }

    public function updateParfume(Request $request, Parfume $parfume) {
        $rules = [
            'parfume_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', implode("\n", $validator->errors()->all()));
        }

        $data = $request->only(['parfume_name','description']);

        try {
            $parfume->update($data);
            ActivityLogController::log('Update', 'Parfume Di Ubah : ' . $data['parfume_name'], auth()->user()->user_id);

            return redirect()->route('admin.parfumes.index')
                ->with('success', 'Parfume updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't update the Parfume. Please try again.")
                ->withInput();
        }

    }

    public function deleteParfume(Parfume $parfume) {
        try {
            $parfume->delete();
            ActivityLogController::log('Delete', 'Parfume Di Hapus : ' . $parfume->parfume_name, auth()->user()->user_id);

            return redirect()->route('admin.parfumes.index')
                ->with('success', 'Parfume deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't delete the Parfume. Please try again.");
        }
    }

    public function importParfume(Request $request)
    {
        try {
            Excel::import(new ParfumeImport, $request->file('file')->store('temp'));
            ActivityLogController::log('Create', 'Parfum Ditambahkan Secara masal ', auth()->user()->user_id);

            return redirect()->route('admin.parfumes.index')
                ->with('success', 'Parfumes added successfully!');
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', "Oops! We couldn't add the parfume. Please try again.")
                ->withInput();
        }
    }

    public function exportParfume()
    {
        try {
            // ActivityLogController::log('Other', 'Parfume Diexport', auth()->user()->user_id);

            return Excel::download(new ParfumeExport, 'parfumes.xlsx');
        } catch (\Exception $e) {
            Log::error('export Service Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't export the parfume. Please try again.");
        }
    }
}
