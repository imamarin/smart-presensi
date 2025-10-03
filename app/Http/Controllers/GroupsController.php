<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class GroupsController extends Controller
{
    // List all groups
    public function index(Request $request)
    {
        $groups = Group::paginate(10);
        if ($request->has('search')){
            $groups = Group::where('nama', 'LIKE', '%'. $request->search .'%')->paginate(10);
        } elseif ($request->sort && $request->direction) {
            $groups = Group::orderBy($request->sort, $request->direction)->paginate(10);
        } else {
            $groups = Group::paginate(10);
        }
        // return response()->json($groups);
        return view('Managment.Group.groups', compact('groups'));
    }

    public function create(){
        return view('Managment.Group.create');
    }

    // Store a new group
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:groups',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return redirect()->back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();

        $group = Group::create($validated);

        Alert::success('Berhasil!', 'Group berhasil dibuat 🎉');
        return redirect()->route('group.index')->with('success', 'Group created successfully');
    }

    public function addParticipant($id)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);
        if (!$group) {
            Alert::error('Gagal!', 'Group tidak ditemukan!');
            return redirect()->route('group.index');
        }
        $participants = Participant::paginate(15);
        $groupParticipantIds = Group::find($groupId)->participants()->pluck('participants.id')->toArray();

        return view('Managment.Group.groupParticipant.create', compact('group', 'participants', 'groupParticipantIds'));
    }

    public function removeParticipant($id)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);
        if (!$group) {
            Alert::error('Gagal!', 'Group tidak ditemukan!');
            return redirect()->route('group.index');
        }

        $participants = Participant::with('groupParticipants')->whereHas('groupParticipants', function($query) use($groupId) {
            $query->where('id_group', $groupId);
        })->paginate(15);
        return view('Managment.Group.groupParticipant.remove', compact('group', 'participants'));

    }



    // Show a single group
    public function show(Request $request, $id)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::with('participants')->find($groupId);
        if (!$group) {
            Alert::error('Gagal!', 'Group tidak ditemukan!');
            return redirect()->route('group.index');
        }
        $participantsQuery = $group->participants();

        if ($request->search) {
            $participantsQuery->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('no_induk', 'like', '%' . $request->search . '%')
                ->orWhere('id_kartu', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->sort && $request->direction) {
            $participantsQuery->orderBy($request->sort, $request->direction);
        }

        $participants = $participantsQuery->paginate(10);

        return view('Managment.Group.show', compact('group', 'participants'));
    }

    public function edit($id)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);
        if (!$group) {
            Alert::error('Gagal!', 'Group tidak ditemukan!');
            return redirect()->route('group.index');
        }
        return view('Managment.Group.edit', compact('group'));
    }

    // Update a group
    public function update(Request $request, $id)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);
        if (!$group) {
            Alert::error('Gagal!', 'Group tidak ditemukan!');
            return redirect()->route('group.index');
        }

        $validated = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255|unique:groups,nama,' . $group->id,
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return redirect()->back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();
        $group->update($validated);

        Alert::success('Berhasil!', 'Group berhasil diupdate 🎉');
        return redirect()->route('group.index')->with('success', 'Group updated successfully');
    }

    // Delete a group
    public function destroy($id)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);
        if (!$group) {
            Alert::error('Gagal!', 'Group tidak ditemukan!');
            return redirect()->route('group.index');
        }
        // Check if the group has participants
        if ($group->participants()->count() > 0) {
            Alert::error('Gagal!', 'Group memiliki peserta!');
            return redirect()->route('group.index');
        }
        $group->delete();

        Alert::success('Berhasil!', 'Group berhasil dihapus 🎉');
        return redirect()->route('group.index')->with('success', 'Group deleted successfully');
    }
}
