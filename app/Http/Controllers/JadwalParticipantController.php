<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\JadwalParticipant;
use App\Models\Shift;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class JadwalParticipantController extends Controller
{
    // List all jadwal participants
    public function index()
    {
        $jadwalParticipants = JadwalParticipant::with(['shift', 'participant'])->get();
        $shifts = Shift::all();
        return view('Managment.JadwalParticipant.jadwalParticipants', compact('jadwalParticipants', 'shifts'));
    }

    public function remove(Request $request)
    {
        $shiftId = Crypt::decrypt($request->id_shift);
        $shift = Shift::find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('jadwalParticipant.index')->withErrors(['message' => 'Shift not found']);
        }
        $participants =  Participant::whereHas('jadwalParticipant', function ($query) use ($shift) {
            $query->where('id_shift', $shift->id);
        })->paginate(15);
        return view('Managment.JadwalParticipant.remove', compact('shift', 'participants'));
    }

    public function create(Request $request)
    {
        $shiftId = Crypt::decrypt($request->id_shift);
        $shift = Shift::find($shiftId);
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('jadwalParticipant.index')->withErrors(['message' => 'Shift not found']);
        }
        $grups = Group::all();

        if($request->filter_grup === 'not'){
            $jadwalParticipants = JadwalParticipant::all();
            $participants = Participant::whereNotIn('id', $jadwalParticipants->pluck('id'))->paginate(15);
        } else if($request->filter_grup && $request->filter_grup != 'all') {
            $participants =  Participant::with('groupParticipants')->whereHas('groupParticipants', function($query) use($request) {
                $query->where('id_group', $request->filter_grup);
            })->paginate(15);
        }else{
            $participants = Participant::paginate(15);
        }

        if($request->search){
            $participants = $participants->filter(function($participant) use ($request) {
                return str_contains(strtolower($participant->nama), strtolower($request->search));
            });
        }

        $jadwalParticipantIds = JadwalParticipant::where('id_shift', $shift->id)
            ->pluck('id_participant')
            ->toArray();

        return view('Managment.JadwalParticipant.create', compact('shift', 'grups', 'participants', 'jadwalParticipantIds'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id_shift' => 'required|exists:shifts,id',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        $ids = explode(',', $request->selected_participants);
        foreach ($ids as $participantId) {
            $existingJadwalParticipant = JadwalParticipant::where('id_participant', $participantId)
                ->first();
            if ($existingJadwalParticipant) {
                JadwalParticipant::where('id_participant', $participantId)
                    ->update(['id_shift' => $validated['id_shift']]);
                continue;
            }

            JadwalParticipant::create([
                'id_shift' => $validated['id_shift'],
                'id_participant' => $participantId,
            ]);
        }

        Alert::success('Berhasil!', 'JadwalParticipant berhasil dibuat 🎉');
        return redirect()->route('jadwalParticipant.index')->with('success', 'JadwalParticipant created successfully');
    }

    // Show a single jadwal participant
    public function show(Request $request)
    {
        $shiftId = Crypt::decrypt($request->id_shift);
        $shift = Shift::find($shiftId);
        $jadwalParticipant = JadwalParticipant::with(['shift', 'participant'])
            ->where('id_shift', $shiftId)
            ->get();
        if ($jadwalParticipant->isEmpty()) {
            Alert::error('Gagal!', 'JadwalParticipant tidak ditemukan!');
            return redirect()->route('jadwalParticipant.index')->withErrors(['message' => 'JadwalParticipant not found']);
        }
    
        if (!$shift) {
            Alert::error('Gagal!', 'Shift tidak ditemukan!');
            return redirect()->route('jadwalParticipant.index')->withErrors(['message' => 'Shift not found']);
        }
        $participants =  Participant::whereHas('jadwalParticipant', function ($query) use ($shift) {
            $query->where('id_shift', $shift->id);
        })->get();
        return view('Managment.JadwalParticipant.show', compact('shift', 'participants', 'jadwalParticipant'));
    }

    // Update a jadwal participant
    public function update(Request $request, $id)
    {
        $jadwalParticipant = JadwalParticipant::findOrFail($id);

        $validated = $request->validate([
            'id_shift' => 'sometimes|required|exists:shifts,id',
        ]);
        if (isset($validated['id_shift'])) {
            $existingJadwalParticipant = JadwalParticipant::where('id_shift', $validated['id_shift'])
                ->where('id_participant', $jadwalParticipant->id_participant)
                ->first();
            if ($existingJadwalParticipant) {
                Alert::error('Gagal!', 'JadwalParticipant sudah ada!');
                return back()->withErrors(['message' => 'JadwalParticipant already exists'])->withInput();
            }
        }

        $jadwalParticipant->update($validated);

        Alert::success('Berhasil!', 'JadwalParticipant berhasil diupdate 🎉');
        return redirect()->route('jadwalParticipant.index')->with('success', 'JadwalParticipant updated successfully');
    }

    // Delete a jadwal participant
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_shift' => 'required|exists:shifts,id',
        ]);
        if ($validator->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $ids = explode(',', $request->selected_participants);
        foreach ($ids as $participantId) {
            JadwalParticipant::where('id_shift', $validated['id_shift'])
                ->where('id_participant', $participantId)
                ->delete();
        }

        Alert::success('Berhasil!', 'JadwalParticipant berhasil dihapus 🎉');
        return redirect()->route('jadwalParticipant.index')->with('success', 'JadwalParticipant deleted successfully');
    }
}