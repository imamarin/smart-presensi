@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Tambah Device Baru</h3>
            <p class="text-muted mb-0">Tambah perangkat baru ke sistem</p>
        </div>
        <a href="{{ route('device.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Device
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('device.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Nama Device -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   placeholder="Nama Device"
                                   value="{{ old('nama') }}"
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
                                   value="{{ old('device_id') }}"
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
                                   value="{{ old('lokasi') }}">
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
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <i class="ti ti-device-plus me-1"></i> Simpan Device
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2">
                        <i class="ti ti-reload me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection