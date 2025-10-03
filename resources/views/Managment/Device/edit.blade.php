@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Edit Device</h3>
            <p class="text-muted mb-0">Ubah data perangkat</p>
        </div>
        <a href="{{ route('device.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Device
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('device.update', Crypt::encrypt($device->id)) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Nama Device -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   placeholder="Nama Device"
                                   value="{{ old('nama', $device->nama) }}"
                                   required>
                            <label for="nama">Nama Device</label>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Device ID -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('device_id') is-invalid @enderror"
                                   id="device_id"
                                   name="device_id"
                                   placeholder="Device ID"
                                   value="{{ old('device_id', $device->device_id) }}"
                                   required>
                            <label for="device_id">Device ID</label>
                            @error('device_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('lokasi') is-invalid @enderror"
                                   id="lokasi"
                                   name="lokasi"
                                   placeholder="Lokasi Device"
                                   value="{{ old('lokasi', $device->lokasi) }}">
                            <label for="lokasi">Lokasi</label>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="active" {{ old('status', $device->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $device->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="status">Status</label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-device-floppy me-1"></i> Update Device
                    </button>
                    <a href="{{ route('device.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="ti ti-reload me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection