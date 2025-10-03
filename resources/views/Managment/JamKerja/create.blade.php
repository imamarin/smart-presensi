@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Create New Jam Kerja</h3>
            <p class="text-muted mb-0">Add a new work schedule to the system</p>
        </div>
        <a href="{{ route('jamKerja.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Jam Kerja List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('jamKerja.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Nama Jam Kerja -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   placeholder="Nama Jam Kerja"
                                   value="{{ old('nama') }}"
                                   required>
                            <label for="nama">Nama Jam Kerja</label>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jam Masuk -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="time"
                                   class="form-control @error('jam_masuk') is-invalid @enderror"
                                   id="jam_masuk"
                                   name="jam_masuk"
                                   placeholder="Jam Masuk"
                                   value="{{ old('jam_masuk') }}"
                                   required>
                            <label for="jam_masuk">Jam Masuk</label>
                            @error('jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jam Pulang -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="time"
                                   class="form-control @error('jam_pulang') is-invalid @enderror"
                                   id="jam_pulang"
                                   name="jam_pulang"
                                   placeholder="Jam Pulang"
                                   value="{{ old('jam_pulang') }}"
                                   required>
                            <label for="jam_pulang">Jam Pulang</label>
                            @error('jam_pulang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Toleransi Terlambat (menit) -->


                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="number"
                                   class="form-control @error('toleransi_terlambat') is-invalid @enderror"
                                   id="toleransi_terlambat"
                                   name="toleransi_terlambat"
                                   placeholder="Toleransi Terlambat"
                                   value="{{ old('toleransi_terlambat', 0) }}"
                                   min="0"
                                   required>
                            <label for="toleransi_terlambat">Toleransi Terlambat (menit)</label>
                            @error('toleransi_terlambat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Toleransi Pulang Cepat (menit) -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="number"
                                   class="form-control @error('toleransi_pulang_cepat') is-invalid @enderror"
                                   id="toleransi_pulang_cepat"
                                   name="toleransi_pulang_cepat"
                                   placeholder="Toleransi Pulang Cepat"
                                   value="{{ old('toleransi_pulang_cepat', 0) }}"
                                   min="0"
                                   required>
                            <label for="toleransi_pulang_cepat">Toleransi Pulang Cepat (menit)</label>
                            @error('toleransi_pulang_cepat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jam Mulai Scan Masuk -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="time"
                                   class="form-control @error('jam_mulai_scan_masuk') is-invalid @enderror"
                                   id="jam_mulai_scan_masuk"
                                   name="jam_mulai_scan_masuk"
                                   placeholder="Jam Mulai Scan Masuk"
                                   value="{{ old('jam_mulai_scan_masuk') }}"
                                   required>
                            <label for="jam_mulai_scan_masuk">Jam Mulai Scan Masuk</label>
                            @error('jam_mulai_scan_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jam Mulai Scan Keluar -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="time"
                                   class="form-control @error('jam_mulai_scan_keluar') is-invalid @enderror"
                                   id="jam_mulai_scan_keluar"
                                   name="jam_mulai_scan_keluar"
                                   placeholder="Jam Mulai Scan Keluar"
                                   value="{{ old('jam_mulai_scan_keluar') }}"
                                   required>
                            <label for="jam_mulai_scan_keluar">Jam Mulai Scan Keluar</label>
                            @error('jam_mulai_scan_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="number"
                                   class="form-control @error('toleransi_check_out') is-invalid @enderror"
                                   id="toleransi_check_out"
                                   name="toleransi_check_out"
                                   placeholder="Toleransi Check Out"
                                   value="{{ old('toleransi_check_out', 0) }}"
                                   min="0"
                                   required>
                            <label for="toleransi_check_out">Toleransi Check Out (Jam)</label>
                            @error('toleransi_check_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-clock-plus me-1"></i> Create Jam Kerja
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
