@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Edit Shift</h3>
            <p class="text-muted mb-0">Update shift details</p>
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
            <form action="{{ route('shift.update', Crypt::encrypt($shift->id)) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Nama Shift -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   placeholder="Nama Shift"
                                   value="{{ old('nama', $shift->nama) }}"
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
                                   value="{{ old('tanggal_mulai', $shift->tanggal_mulai) }}"
                                   required>
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Jam Kerja -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('id_jam_kerja') is-invalid @enderror"
                                    id="id_jam_kerja"
                                    name="id_jam_kerja"
                                    required>
                                <option value="" disabled>Pilih Jam Kerja</option>
                                @foreach($jamKerjas as $jamKerja)
                                    <option value="{{ $jamKerja->id }}"
                                        {{ old('id_jam_kerja', $shift->id_jam_kerja) == $jamKerja->id ? 'selected' : '' }}>
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
                        <i class="ti ti-device-floppy me-1"></i> Update Shift
                    </button>
                    <a href="{{ route('shift.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="ti ti-reload me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
