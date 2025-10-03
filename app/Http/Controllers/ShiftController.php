<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ShiftController extends Controller
{
    // List all shifts
    public function index(Request $request)
    {
        $shifts = Shift::with('jamKerja', 'detailShifts')->paginate(10);

        if ($request->search) {
            $shifts = Shift::where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('tanggal_mulai', 'like', '%' . $request->search . '%')
                ->orWhereHas('jamKerja', function ($query) use ($request) {
                    $query->where('nama', 'like', '%' . $request->search . '%');
                })
                ->paginate(10);
        } elseif ($request->sort && $request->direction) {
            $shifts = Shift::orderBy($request->sort, $request->direction)->paginate(10);
        }

        return view('Managment.Shift.shifts', compact('shifts'));
    }

    public function create()
    {
        $jamKerjas = JamKerja::all();
        return view('Managment.Shift.create', compact('jamKerjas'));
    }

    public function createDetailShift($shift_id)
    {
        $shiftId = Crypt::decrypt($shift_id);
        $shift = Shift::with('jamKerja', 'detailShifts')->find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('shift.index');
        }
        $detailShiftHari = $shift->detailShifts->pluck('hari')->toArray();
        // return response()->json($detailShiftHari);
        return view('Managment.Shift.detailShift.create', compact('shift', 'detailShiftHari'));
    }

    // Store a new shift
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'id_jam_kerja' => 'required|exists:jam_kerjas,id',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        $existingShift = Shift::where('nama', $validated['nama'])
            ->where('tanggal_mulai', $validated['tanggal_mulai'])
            ->where('id_jam_kerja', $validated['id_jam_kerja'])
            ->first();
        if ($existingShift) {
            Alert::error('Gagal!', 'Shift sudah ada!');
            return back()->withErrors(['message' => 'Shift already exists'])->withInput();
        }

        $shift = Shift::create($validated);

        Alert::success('Berhasil!', 'Shift berhasil dibuat 🎉');
        return redirect()->route('shift.index')->with('success', 'Shift created successfully');
    }

    // Show a single shift
    public function show($id)
    {
        $shiftId = Crypt::decrypt($id);
        $shift = Shift::with('jamKerja', 'detailShifts')->find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('shift.index');
        }
        $urutanHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $shift->detailShifts = $shift->detailShifts->sortBy(function($item) use ($urutanHari) {
            return array_search($item->hari, $urutanHari);
        })->values();
        return view('Managment.Shift.show', compact('shift'));
    }

    public function edit($id)
    {
        $shiftId = Crypt::decrypt($id);
        $shift = Shift::with('jamKerja', 'detailShifts')->find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('shift.index');
        }
        $jamKerjas = JamKerja::all();
        return view('Managment.Shift.edit', compact('shift', 'jamKerjas'));
    }

    // Update a shift
    public function update(Request $request, $id)
    {
        $shiftId = Crypt::decrypt($id);
        $shift = Shift::with('jamKerja', 'detailShifts')->find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('shift.index');
        }
        $validated = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:100',
            'tanggal_mulai' => 'sometimes|required|date',
            'id_jam_kerja' => 'sometimes|required|exists:jam_kerjas,id',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        $existingShift = Shift::where('nama', $validated['nama'])
            ->where('tanggal_mulai', $validated['tanggal_mulai'])
            ->where('id_jam_kerja', $validated['id_jam_kerja'])
            ->where('id', '!=', $shift->id)
            ->first();
        if ($existingShift) {
            Alert::error('Gagal!', 'Shift sudah ada!');
            return back()->withErrors(['message' => 'Shift already exists'])->withInput();
        }

        $shift->update($validated);

        Alert::success('Berhasil!', 'Shift berhasil diupdate 🎉');
        return redirect()->route('shift.index')->with('success', 'Shift updated successfully');
    }

    // Delete a shift
    public function destroy($id)
    {
        $shiftId = Crypt::decrypt($id);
        $shift = Shift::with('jamKerja', 'detailShifts')->find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('shift.index');
        }

        if ($shift->jadwal_participant()->exists()) {
            Alert::error('Gagal!', 'Shift tidak bisa dihapus karena memiliki jadwal peserta!');
            return back()->withErrors(['message' => 'Shift cannot be deleted because it has jadwal participants'])->withInput();
        }

        $shift->delete();

        Alert::success('Berhasil!', 'Shift berhasil dihapus 🎉');
        return redirect()->route('shift.index')->with('success', 'Shift deleted successfully');
    }
}
