<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaktuLibur;
use App\Models\GroupLibur;
use App\Models\Group;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class WaktuLiburController extends Controller
{
    // List all waktu libur
    public function index(Request $request)
    {
        if($request->has('search')) {
            $search = $request->input('search');
            $waktuLiburs = WaktuLibur::where('nama_libur', 'like', "%{$search}%")
                ->orWhere('tanggal_mulai', 'like', "%{$search}%")
                ->orWhere('tanggal_akhir', 'like', "%{$search}%")
                ->paginate(10);
        } else if($request->sort && $request->direction) {
            $waktuLiburs = WaktuLibur::orderBy($request->sort, $request->direction)->paginate(10);
        } else {
            $waktuLiburs = WaktuLibur::paginate(10);
        }

        return view('Managment.WaktuLibur.waktuLiburs', compact('waktuLiburs'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('Managment.WaktuLibur.create', compact('groups'));
    }

    // Store a new waktu libur
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama_libur' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        $existingWaktuLibur = WaktuLibur::where('nama_libur', $validated['nama_libur'])
            ->where('tanggal_mulai', $validated['tanggal_mulai'])
            ->where('tanggal_akhir', $validated['tanggal_akhir'])
            ->first();
        if ($existingWaktuLibur) {
            Alert::error('Gagal!', 'WaktuLibur sudah ada!');
            return back()->withErrors(['message' => 'WaktuLibur already exists'])->withInput();
        }

        $waktuLiburId = WaktuLibur::create($validated)->id;
        if ($request->has('groups')) {
            foreach ($request->groups as $groupId) {
                GroupLibur::create([
                    'id_waktu_libur' => $waktuLiburId,
                    'id_group' => $groupId,
                ]);
            }
        }

        Alert::success('Berhasil!', 'Waktu Libur berhasil dibuat 🎉');
        return redirect()->route('waktuLibur.index')->with('success', 'Waktu Libur created successfully');
    }

    // Show a single waktu libur
    public function show($id)
    {
        $waktuLiburId = Crypt::decrypt($id);
        $waktuLibur = WaktuLibur::with('groupLibur')->find($waktuLiburId);
        if (!$waktuLibur) {
            Alert::error('Gagal!', 'WaktuLibur tidak ditemukan!');
            return redirect()->route('waktuLibur.index')->withErrors(['message' => 'WaktuLibur not found']);
        }

        $groupLibur = $waktuLibur->groupLibur;
        $groups = $groupLibur->map(function ($item) {
            return $item->group;
        });
        return view('Managment.WaktuLibur.show', compact('waktuLibur', 'groups'));
    }

    public function edit($id)
    {
        $waktuLiburId = Crypt::decrypt($id);
        $waktuLibur = WaktuLibur::with('groupLibur')->find($waktuLiburId);
        if (!$waktuLibur) {
            Alert::error('Gagal!', 'WaktuLibur tidak ditemukan!');
            return redirect()->route('waktuLibur.index')->withErrors(['message' => 'WaktuLibur not found']);
        }

        $groupLibur = $waktuLibur->groupLibur;
        $groups = Group::all();
        $selectedGroups = $groupLibur->pluck('id_group')->toArray();

        return view('Managment.WaktuLibur.edit', compact('waktuLibur', 'groups', 'selectedGroups'));
    }

    // Update a waktu libur
    public function update(Request $request, $id)
    {
        $waktuLiburId = Crypt::decrypt($id);
        $waktuLibur = WaktuLibur::with('groupLibur')->find($waktuLiburId);
        if (!$waktuLibur) {
            Alert::error('Gagal!', 'WaktuLibur tidak ditemukan!');
            return redirect()->route('waktuLibur.index')->withErrors(['message' => 'WaktuLibur not found']);
        }

        $validated = Validator::make($request->all(), [
            'nama_libur' => 'sometimes|required|string|max:255',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_akhir' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        $existingWaktuLibur = WaktuLibur::where('nama_libur', $validated['nama_libur'])
            ->where('tanggal_mulai', $validated['tanggal_mulai'])
            ->where('tanggal_akhir', $validated['tanggal_akhir'])
            ->where('id', '!=', $waktuLiburId)
            ->first();
        if ($existingWaktuLibur) {
            Alert::error('Gagal!', 'WaktuLibur sudah ada!');
            return back()->withErrors(['message' => 'WaktuLibur already exists'])->withInput();
        }

        $waktuLibur->update($validated);

        if ($request->has('groups')) {
            foreach ($request->groups as $groupId) {
                GroupLibur::create([
                    'id_waktu_libur' => $waktuLiburId,
                    'id_group' => $groupId,
                ]);
            }
        }

        Alert::success('Berhasil!', 'Waktu Libur berhasil diupdate 🎉');
        return redirect()->route('waktuLibur.index')->with('success', 'Waktu Libur updated successfully');
    }

    // Delete a waktu libur
    public function destroy($id)
    {
        $waktuLiburId = Crypt::decrypt($id);
        $waktuLibur = WaktuLibur::with('groupLibur')->find($waktuLiburId);
        if (!$waktuLibur) {
            Alert::error('Gagal!', 'WaktuLibur tidak ditemukan!');
            return redirect()->route('waktuLibur.index')->withErrors(['message' => 'WaktuLibur not found']);
        }
        $waktuLibur->delete();

        Alert::success('Berhasil!', 'Waktu Libur berhasil dihapus 🎉');
        return redirect()->route('waktuLibur.index')->with('success', 'Waktu Libur deleted successfully');
    }
}