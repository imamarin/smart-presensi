<?php

namespace App\Http\Controllers;

use App\Models\WaktuLibur;
use Illuminate\Http\Request;
use App\Models\GroupLibur;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class GroupLiburController extends Controller
{
    // List all group libur
    public function index()
    {
        $groupLiburs = GroupLibur::with(['waktuLibur', 'group'])->get();
        return response()->json($groupLiburs);
    }

    // Store a new group libur
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id_waktu_libur' => 'required|exists:waktu_liburs,id',
            'id_group' => 'required|exists:groups,id',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $validated = $validated->validated();
        // Check if the group libur already exists
        $existingGroupLibur = GroupLibur::where('id_waktu_libur', $validated['id_waktu_libur'])
            ->where('id_group', $validated['id_group'])
            ->first();
        if ($existingGroupLibur) {
            return response()->json(['message' => 'GroupLibur already exists'], 422);
        }

        $groupLibur = GroupLibur::create($validated);

        return response()->json($groupLibur->load(['waktuLibur', 'group']), 201);
    }

    // Show a single group libur
    public function show($id)
    {
        $groupLibur = GroupLibur::with(['waktuLibur', 'group'])->find($id);
        if (!$groupLibur) {
            // return response()->json(['message' => 'GroupLibur not found'], 404);
            return redirect()->route('waktuLibur.index')
                ->withErrors(['message' => 'Group Libur not found']);
        }
        // return response()->json($groupLibur);
        // return view('Managment.GroupLibur.show', compact('groupLibur'));
    }

    // Update a group libur
    public function update(Request $request, $id)
    {
        $groupLibur = GroupLibur::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'id_waktu_libur' => 'sometimes|required|exists:waktu_liburs,id',
            'id_group' => 'sometimes|required|exists:groups,id',
        ]);
        if ($validated->fails()) {
            // return response()->json($validated->errors(), 422);
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();
        // Check if the group libur already exists
        if (isset($validated['id_waktu_libur']) && isset($validated['id_group'])) {
            $existingGroupLibur = GroupLibur::where('id_waktu_libur', $validated['id_waktu_libur'])
                ->where('id_group', $validated['id_group'])
                ->where('id', '!=', $id)
                ->first();
            if ($existingGroupLibur) {
                // return response()->json(['message' => 'GroupLibur already exists'], 422);
                return back()->withErrors(['message' => 'Group Libur already exists'])->withInput();
            }
        }

        $groupLibur->update($validated);

        // return response()->json($groupLibur->load(['waktuLibur', 'group']));
        return redirect()->route('waktuLibur.show', $groupLibur->id_waktu_libur)
            ->with('success', 'Group Libur updated successfully');
    }

    // Delete a group libur
    public function destroy($id_group, $id_waktu_libur)
    {
        $groupId = Crypt::decrypt($id_group);
        $waktuLiburId = Crypt::decrypt($id_waktu_libur);
        $waktuLibur = GroupLibur::where('id_group', $groupId)
            ->where('id_waktu_libur', $waktuLiburId)
            ->first();
        if (!$waktuLibur) {
            // return response()->json(['message' => 'GroupLibur not found'], 404);
            return redirect()->back()->withErrors(['message' => 'Group Libur not found']);
        }
        $waktuLibur->delete();

        return redirect()->back()->with('success', 'Group berhasil dihapus dari waktu libur.');
    }
}
