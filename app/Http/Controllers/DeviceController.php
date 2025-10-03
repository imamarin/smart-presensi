<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class DeviceController extends Controller
{
    // List all devices
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $search = $request->input('search');
            $devices = Device::where('nama', 'like', "%{$search}%")
                ->orWhere('device_id', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%")
                ->paginate(10);

            $devices->setCollection(
                $devices->getCollection()->map(function ($device) {
                    $device->status_koneksi = now()->diffInMinutes($device->status_koneksi) <= 10 ? 'Connect' : 'Disconnect';
                    return $device;
                })
            );
        } elseif ($request->sort && $request->direction) {
            $devices = Device::orderBy($request->sort, $request->direction)
                ->paginate(10);

            $devices->setCollection(
                $devices->getCollection()->map(function ($device) {
                    $device->status_koneksi = now()->diffInMinutes($device->status_koneksi) <= 10 ? 'Connect' : 'Disconnect';
                    return $device;
                })
            );
        } else {
            $devices = Device::paginate(10);
            $devices->setCollection(
                $devices->getCollection()->map(function ($device) {
                    $device->status_koneksi = now()->diffInMinutes($device->status_koneksi) <= 10 ? 'Connect' : 'Disconnect';
                    return $device;
                })
            );
        }

        return view('Managment.Device.devices', compact('devices'));
    }

    public function create()
    {
        return view('Managment.Device.create');
    }

    // Store a new device
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'device_id' => 'required|string|max:50|unique:devices',
            'lokasi' => 'nullable|string|max:150',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();
        $validated['api_key'] = Str::random(32);

        $device = Device::create($validated);

        Alert::success('Berhasil!', 'Device berhasil dibuat 🎉');
        return redirect()->route('device.index')->with('success', 'Device created successfully');
    }

    // Show a single device
    public function show($id)
    {
        $deviceId = Crypt::decrypt($id);
        $device = Device::find($deviceId);
        $device->status_koneksi_badge = now()->diffInMinutes($device->status_koneksi) <= 10 ? 'Connect' : 'Disconnect';
        
        if (!$device) {
            Alert::error('Gagal!', 'Device tidak ditemukan!');
            return redirect()->route('device.index')->withErrors(['message' => 'Device not found']);
        }
        return view('Managment.Device.show', compact('device'));
    }

    public function edit($id)
    {
        $deviceId = Crypt::decrypt($id);
        $device = Device::find($deviceId);
        if (!$device) {
            Alert::error('Gagal!', 'Device tidak ditemukan!');
            return redirect()->route('device.index')->withErrors(['message' => 'Device not found']);
        }
        return view('Managment.Device.edit', compact('device'));
    }

    // Update a device
    public function update(Request $request, $id)
    {
        $deviceId = Crypt::decrypt($id);
        $device = Device::find($deviceId);
        if (!$device) {
            Alert::error('Gagal!', 'Device tidak ditemukan!');
            return redirect()->route('device.index')->withErrors(['message' => 'Device not found']);
        }

        $validated = Validator::make($request->all(), [
            'device_id' => 'required|string|max:50|unique:devices',
            'nama' => 'sometimes|required|string|max:100',
            'lokasi' => 'nullable|string|max:150',
            'status' => 'sometimes|required|in:active,inactive',
        ]);
        if ($validated->fails()) {
            Alert::error('Gagal!', 'Validasi gagal!');
            return back()->withErrors($validated)->withInput();
        }
        $validated = $validated->validated();
        if (isset($validated['device_id'])) {
            $existingDevice = Device::where('device_id', $validated['device_id'])
                ->where('id', '!=', $deviceId)
                ->first();
            if ($existingDevice) {
                Alert::error('Gagal!', 'Device ID sudah ada!');
                return back()->withErrors(['message' => 'Device ID already exists'])->withInput();
            }
        }

        $device->update($validated);

        Alert::success('Berhasil!', 'Device berhasil diupdate 🎉');
        return redirect()->route('device.index')->with('success', 'Device updated successfully');
    }

    // Delete a device
    public function destroy($id)
    {
        $deviceId = Crypt::decrypt($id);
        $device = Device::find($deviceId);
        if (!$device) {
            Alert::error('Gagal!', 'Device tidak ditemukan!');
            return redirect()->route('device.index')->withErrors(['message' => 'Device not found']);
        }
        if ($device->presensi()->count() > 0) {
            Alert::error('Gagal!', 'Device sedang digunakan dan tidak bisa dihapus!');
            return redirect()->route('device.index')->withErrors(['message' => 'Device is in use and cannot be deleted']);
        }
        $device->delete();

        Alert::success('Berhasil!', 'Device berhasil dihapus 🎉');
        return redirect()->route('device.index')->with('success', 'Device deleted successfully');
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'device_id' => 'required|integer',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 401);
        }

        $device = Device::findOrFail($request->device_id);
        return response()->json(['api_key' => $device->api_key]);
    }
}