@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Create New Shift</h3>
            <p class="text-muted mb-0">Add a new shift to the system</p>
        </div>
        <a href="{{ route('shift.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Shift List
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
            <form action="{{ route('shift.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Nama Shift -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   placeholder="Nama Shift"
                                   value="{{ old('nama') }}"
                                   required>
                            <label for="nama">Nama Shift</label>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date"
                                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   id="tanggal_mulai"
                                   name="tanggal_mulai"
                                   placeholder="Tanggal Mulai"
                                   value="{{ old('tanggal_mulai') }}"
                                   required>
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Hitungan Lembur -->


                    <!-- Jam Kerja -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('id_jam_kerja') is-invalid @enderror"
                                    id="id_jam_kerja"
                                    name="id_jam_kerja"
                                    required>
                                <option value="" disabled selected>Pilih Jam Kerja</option>
                                @foreach($jamKerjas as $jamKerja)
                                    <option value="{{ $jamKerja->id }}" {{ old('id_jam_kerja') == $jamKerja->id ? 'selected' : '' }}>
                                        {{ $jamKerja->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="id_jam_kerja">Jam Kerja</label>
                            @error('id_jam_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-clock-plus me-1"></i> Create Shift
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
