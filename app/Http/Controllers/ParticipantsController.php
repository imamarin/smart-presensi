<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantImport;
use App\Exports\ParticipantExport;
use App\Exports\ParticipantGroupExport;
use Illuminate\Validation\Rule;


class ParticipantsController extends Controller
{
    // List all participants
    public function index(Request $request)
    {
        // $participants = Participant::all();
        $participants = Participant::paginate(10); // Dpagination

        if ($request->search){
            $participants = Participant::where('nama', 'like', '%' . $request->search .'%')
                ->orWhere('no_induk', 'like', '%' . $request->search . '%')
                ->orWhere('id_kartu', 'like', '%' . $request->search . '%')
                ->orWhere('id_fingerprint', 'like', '%' . $request->search . '%')
                ->paginate(10);
        } elseif ($request->sort && $request->direction) {
            $participants = Participant::orderBy($request->sort, $request->direction)->paginate(10);
        } else {
            $participants = Participant::paginate(10);
        }



        // return response()->json($participants);
        return view('Managment.Participant.participants', compact('participants'));
    }

    public function create()
    {
        // Assuming you want to return a view for creating a new participant
        return view('Managment.Participant.create');
    }

    // Store a new participant
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'no_induk' => 'required|string|max:50|unique:participants',
            'id_kartu' => 'required|string|max:50|unique:participants',
            'id_fingerprint' => 'required|string|max:50|unique:participants',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);
        
        if ($validated->fails()) {
            // return response()->json($validated->errors(), 422);
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();
        $participant = Participant::create($validated);
        Alert::success('Berhasil!', 'Participant berhasil ditambahkan 🎉');
        
        // return response()->json($participant, 201);
        // return back()->with('success', 'Participant created successfully');
        return redirect()->route('participant.index')->with('success', 'Participant created successfully');
    }
    
    // Show a single participant
    public function show($id)
    {
        $participantId = Crypt::decrypt($id);
        $participant = Participant::with('groupParticipants', 'groupParticipants.group', 'presensi')->find($participantId);
        if (!$participant) {
            return response()->json(['message' => 'Participant not found'], 404);
        }
        $presensis = $participant->presensi;
        $groupParticipant = $participant->groupParticipants;
        // return response()->json($groupParticipant);
        
        // return response()->json($participant);
        return view('Managment.Participant.show', compact('participant', 'presensis'));
        // return view('Managment.Participant.import');
    }
    
    // show a participant by id_kartu
    public function showByIdKartu($id_kartu)
    {
        $participant = Participant::where('id_kartu', $id_kartu)->first();
        if (!$participant) {
            return response()->json(['message' => 'Participant not found'], 404);
        }
        
        // return response()->json($participant);
        return view('Managment.Participant.show', compact('participant'));
    }
    
    public function edit($id)
    {
        $participantId = Crypt::decrypt($id);
        $participant = Participant::find($participantId);
        if (!$participant) {
            // return response()->json(['message' => 'Participant not found'], 404);
            return back()->with('error', 'Participant not found');
        }
        
        // Assuming you want to return a view for editing
        return view('Managment.Participant.edit', compact('participant'));
    }
    
    // Update a participant
    public function update(Request $request, $id)
    {
        $participantId = Crypt::decrypt($id);
        $participant = Participant::findOrFail($participantId);
        
        $validator =  Validator::make($request->all(), [
            'id_kartu' => [
                'required',
                'string',
                'max:50',
                Rule::unique('participants')->ignore($participant->id),
            ],
            'id_fingerprint' => [
                'required',
                'string',
                'max:50',
                Rule::unique('participants')->ignore($participant->id),
            ],
            'nama' => 'sometimes|required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $participant->update($validated);
        Alert::success('Berhasil!', 'Participant berhasil diubah 🎉');

        // return response()->json($participant);
        // return back()->with('success', 'Participant updated successfully');
        return redirect()->route('participant.index')->with('success', 'Participant updated successfully');
    }

    // Delete a participant
    public function destroy($id)
    {
        $participantId = Crypt::decrypt($id);
        $participant = Participant::find($participantId);
        if (!$participant) {
            // return response()->json(['message' => 'Participant not found'], 404);
            return back()->with('error', 'Participant not found');
        }
        $participant->delete();
        Alert::success('Berhasil!', 'Participant berhasil dihapus 🎉');

        // return response()->json(['message' => 'Participant deleted']);
        return back()->with('success', 'Participant deleted successfully');
    }

    public function importView() {
        return view('Managment.Participant.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new ParticipantImport;
        $import->import($request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $messages = [];

            foreach ($import->failures() as $failure) {
                $messages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            Alert::error('Import Gagal', 'Beberapa data gagal diimpor.');
            return back()->withErrors($messages);
        }

        Alert::success('Import Berhasil', 'Data peserta berhasil diimpor!');
        return redirect()->route('participant.index');
    }

    public function exportAll() {
        return Excel::download(new ParticipantExport, 'all-participant.xlsx');
    }

    public function exportByGroup($id)
    {

        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);

        if(!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        return Excel::download(new ParticipantGroupExport($group->id), 'peserta_group_' . $group->nama . '.xlsx');
    }

}
