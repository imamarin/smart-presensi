<?php

namespace App\Http\Controllers;

use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class JamKerjaController extends Controller
{
    // List all jam kerja
    public function index(Request $request)
    {
        if ($request->search) {
            $jamKerjas = JamKerja::where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('jam_masuk', 'like', '%' . $request->search . '%')
                ->orWhere('jam_pulang', 'like', '%' . $request->search . '%')
                ->paginate(10);
        } elseif ($request->sort && $request->direction) {
            $jamKerjas = JamKerja::orderBy($request->sort, $request->direction)->paginate(10);
        } else {
            $jamKerjas = JamKerja::paginate(10);
        }
        return view('Managment.JamKerja.jamKerja', compact('jamKerjas'));
    }

    public function create()
    {
        return view('Managment.JamKerja.create');
    }

    // Store a new jam kerja
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:100|unique:jam_kerjas,nama',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'toleransi_terlambat' => 'required|integer|min:0',
            'toleransi_pulang_cepat' => 'required|integer|min:0',
            'jam_mulai_scan_masuk' => 'required|date_format:H:i',
            'jam_mulai_scan_keluar' => 'required|date_format:H:i',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        if ($validated['jam_masuk'] >= $validated['jam_pulang']) {
            Alert::error('Gagal!', 'Jam masuk harus sebelum jam pulang');
            return back()->withErrors(['jam_masuk' => 'Jam masuk harus sebelum jam pulang'])->withInput();
        }

        if ($validated['jam_mulai_scan_masuk'] >= $validated['jam_mulai_scan_keluar']) {
            Alert::error('Gagal!', 'Jam mulai scan masuk harus sebelum jam mulai scan keluar');
            return back()->withErrors(['jam_mulai_scan_masuk' => 'Jam mulai scan masuk harus sebelum jam mulai scan keluar'])->withInput();
        }

        if ($validated['jam_mulai_scan_masuk'] > $validated['jam_masuk']) {
            Alert::error('Gagal!', 'Jam mulai scan masuk harus sebelum atau sama dengan jam masuk');
            return back()->withErrors(['jam_mulai_scan_masuk' => 'Jam mulai scan masuk harus sebelum atau sama dengan jam masuk'])->withInput();
        }

        if ($validated['jam_mulai_scan_keluar'] > $validated['jam_pulang']) {
            Alert::error('Gagal!', 'Jam mulai scan keluar harus sebelum atau sama dengan jam pulang');
            return back()->withErrors(['jam_mulai_scan_keluar' => 'Jam mulai scan keluar harus sebelum atau sama dengan jam pulang'])->withInput();
        }

        $jamKerja = JamKerja::create($validated);

        Alert::success('Berhasil!', 'Jam Kerja berhasil dibuat 🎉');
        return redirect()->route('jamKerja.index')->with('success', 'Jam Kerja created successfully');
    }

    // Show a single jam kerja
    public function show($id)
    {
        $jamKerjaId = Crypt::decrypt($id);
        $jamKerja = JamKerja::find($jamKerjaId);
        if (!$jamKerja) {
            Alert::error('Gagal!', 'JamKerja tidak ditemukan!');
            return redirect()->route('jamKerja.index')->withErrors(['message' => 'JamKerja not found']);
        }
        return view('Managment.JamKerja.show', compact('jamKerja'));
    }

    public function edit($id)
    {
        $jamKerjaId = Crypt::decrypt($id);
        $jamkerja = JamKerja::find($jamKerjaId);
        if (!$jamkerja) {
            Alert::error('Gagal!', 'JamKerja tidak ditemukan!');
            return redirect()->route('jamKerja.index');
        }
        return view('Managment.JamKerja.edit', compact('jamkerja'));
    }

    // Update a jam kerja
    public function update(Request $request, $id)
    {
        
        $jamKerjaId = Crypt::decrypt($id);
        $jamKerja = JamKerja::find($jamKerjaId);

        $validated = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:100',
            'jam_masuk' => 'sometimes|required|date_format:H:i',
            'jam_pulang' => 'sometimes|required|date_format:H:i',
            'toleransi_terlambat' => 'sometimes|required|integer|min:0',
            'toleransi_pulang_cepat' => 'sometimes|required|integer|min:0',
            'jam_mulai_scan_masuk' => 'sometimes|required|date_format:H:i',
            'jam_mulai_scan_keluar' => 'sometimes|required|date_format:H:i',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }

        $validated = $validated->validated();

        if (JamKerja::where('nama', $validated['nama'])->where('id', '!=', $jamKerjaId)->exists()) {
            Alert::error('Gagal!', 'Nama jam kerja sudah ada1');
            return back()->withErrors(['nama' => 'Nama jam kerja sudah ada'])->withInput();
        }

        if ($validated['jam_masuk'] >= $validated['jam_pulang']) {
            Alert::error('Gagal!', 'Jam masuk harus sebelum jam pulang');
            return back()->withErrors(['jam_masuk' => 'Jam masuk harus sebelum jam pulang'])->withInput();
        }

        if ($validated['jam_mulai_scan_masuk'] >= $validated['jam_mulai_scan_keluar']) {
            Alert::error('Gagal!', 'Jam mulai scan masuk harus sebelum jam mulai scan keluar');
            return back()->withErrors(['jam_mulai_scan_masuk' => 'Jam mulai scan masuk harus sebelum jam mulai scan keluar'])->withInput();
        }

        if ($validated['jam_mulai_scan_masuk'] > $validated['jam_masuk']) {
            Alert::error('Gagal!', 'Jam mulai scan masuk harus sebelum atau sama dengan jam masuk');
            return back()->withErrors(['jam_mulai_scan_masuk' => 'Jam mulai scan masuk harus sebelum atau sama dengan jam masuk'])->withInput();
        }

        if ($validated['jam_mulai_scan_keluar'] > $validated['jam_pulang']) {
            Alert::error('Gagal!', 'Jam mulai scan keluar harus sebelum atau sama dengan jam pulang');
            return back()->withErrors(['jam_mulai_scan_keluar' => 'Jam mulai scan keluar harus sebelum atau sama dengan jam pulang'])->withInput();
        }

        $jamKerja->update($validated);

        Alert::success('Berhasil!', 'Jam Kerja berhasil diupdate 🎉');
        return redirect()->route('jamKerja.index')->with('success', 'Jam Kerja updated successfully');
    }

    // Delete a jam kerja
    public function destroy($id)
    {
        $jamKerjaId = Crypt::decrypt($id);
        $jamKerja = JamKerja::find($jamKerjaId);
        if (!$jamKerja) {
            Alert::error('Gagal!', 'JamKerja tidak ditemukan!');
            return back()->with('error', 'JamKerja not found');
        }
        if ($jamKerja->shift()->count() > 0) {
            Alert::error('Gagal!', 'JamKerja tidak bisa dihapus karena digunakan di shift!');
            return back()->withErrors(['message' => 'JamKerja cannot be deleted because it is used in shifts'])->withInput();
        }

        $jamKerja->delete();

        Alert::success('Berhasil!', 'Jam Kerja berhasil dihapus 🎉');
        return back()->with('message', "Success to delete");
    }
}