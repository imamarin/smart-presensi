<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Crypt;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::all();
        $presensis = null;

        if ($request->has('group_id') && $request->has('start_date') && $request->has('end_date')) {
            $group = Group::find($request->group_id);
            if ($group) {
                $participantIds = $group->participants()->pluck('participants.id');
                $presensis = \App\Models\Presensi::with(['participant', 'shift'])
                    ->whereIn('id_participant', $participantIds)
                    ->whereDate('waktu_masuk', '>=', $request->start_date)
                    ->whereDate('waktu_masuk', '<=', $request->end_date)
                    ->get();
            }
        }

        return view('Managment.Laporan.index', compact('groups', 'presensis'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $group_id = Crypt::encrypt($request->group_id);
        
        // Redirect to the existing presensiExport route to reuse the export logic
        return redirect()->route('export.presensi.group', [
            'id' => $group_id,
            'date1' => $request->start_date,
            'date2' => $request->end_date
        ]);
    }
}
