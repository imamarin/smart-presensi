<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\DetailShift;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DetailShiftController extends Controller
{
    // List all detail shifts
    public function index()
    {
        $detailShifts = DetailShift::with('shift')->get();
        return view('Managment.Shift.shifts');
    }

    // Store a new detail shift
    public function store(Request $request)
    {
        $shiftId = Crypt::decrypt($request->shift_id);
        $validated = Validator::make($request->all(), [
            'days' => 'required',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        // Validate shift exists
        $shift = Shift::find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return back()->withErrors(['message' => 'Shift not found'])->withInput();
        }
        $validated['id_shift'] = $shiftId;

        foreach($request->days as $day) {
            DetailShift::create([
                'hari' => $day,
                'id_shift' => $shiftId
            ]);
        }

        Alert::success('Berhasil!', 'DetailShift berhasil dibuat 🎉');
        return redirect()->route('shift.show', $request->shift_id)->with('success', 'DetailShift created successfully');
    }

    // Show a single detail shift
    public function show($id)
    {
        $detailShift = DetailShift::with('shift')->find($id);
        if (!$detailShift) {
            Alert::error('Gagal!', 'DetailShift tidak ditemukan!');
            return response()->json(['message' => 'DetailShift not found'], 404);
        }
        // return view('Managment.Shift.showDetailShift', compact('detailShift'));
    }

    // Update a detail shift
    public function update(Request $request, $id)
    {
        $detailShift = DetailShift::find($id);
        if (!$detailShift) {
            Alert::error('Gagal!', 'DetailShift tidak ditemukan!');
            return back()->with('error', 'DetailShift not found');
        }

        $validated = Validator::make($request->all(), [
            'hari' => 'sometimes|required|string|max:20',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        // Check if the detail shift already exists
        $existingDetailShift = DetailShift::where('id_shift', $detailShift->id_shift)
            ->where('hari', $validated['hari'])
            ->where('id', '!=', $id)
            ->first();
        if ($existingDetailShift) {
            Alert::error('Gagal!', 'DetailShift sudah ada!');
            return back()->withErrors(['message' => 'DetailShift already exists'])->withInput();
        }

        // validate hari
        $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        if (!in_array(strtolower($validated['hari']), $hari)) {
            Alert::error('Gagal!', 'Hari tidak valid!');
            return back()->withErrors(['message' => 'Hari tidak valid'])->withInput();
        }

        $detailShift->update($validated);

        Alert::success('Berhasil!', 'DetailShift berhasil diupdate 🎉');
        return redirect()->route('shift.show', $detailShift->id_shift)->with('success', 'DetailShift updated successfully');
    }

    // Delete a detail shift
    public function destroy($id)
    {
        $detailShift = DetailShift::find($id);
        if (!$detailShift) {
            Alert::error('Gagal!', 'DetailShift tidak ditemukan!');
            return response()->json(['message' => 'DetailShift not found'], 404);
        }

        $detailShift->delete();

        Alert::success('Berhasil!', 'DetailShift berhasil dihapus 🎉');
        return redirect()->route('shift.show', Crypt::encrypt($detailShift->id_shift))->with('success', 'DetailShift deleted successfully');
    }
}