{{-- filepath: d:\laravel\Project_Presensi\resources\views\Managment\WaktuLibur\edit.blade.php --}}
@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Edit Waktu Libur</h3>
            <p class="text-muted mb-0">Ubah data hari libur</p>
        </div>
        <a href="{{ route('waktuLibur.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Libur
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('waktuLibur.update', Crypt::encrypt($waktuLibur->id)) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Nama Libur -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama_libur') is-invalid @enderror"
                                   id="nama_libur"
                                   name="nama_libur"
                                   placeholder="Contoh: Hari Raya"
                                   value="{{ old('nama_libur', $waktuLibur->nama_libur) }}"
                                   required>
                            <label for="nama_libur">Nama Libur</label>
                            @error('nama_libur')
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
                                   value="{{ old('tanggal_mulai', $waktuLibur->tanggal_mulai) }}"
                                   required>
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date"
                                   class="form-control @error('tanggal_akhir') is-invalid @enderror"
                                   id="tanggal_akhir"
                                   name="tanggal_akhir"
                                   placeholder="Tanggal Akhir"
                                   value="{{ old('tanggal_akhir', $waktuLibur->tanggal_akhir) }}"
                                   required>
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            @error('tanggal_akhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-nowrap">
                                    <tr>
                                        <th class="pe-4">ID</th>
                                        <th class="pe-4">Nama</th>
                                        <th class="text-end pe-4">
                                            <input type="checkbox" name="allCheck" id="allCheck" class="form-check-input" onclick="checkAll(this)">
                                            <label for="allCheck" class="form-check-label">Pilih Semua</label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse ($groups as $group)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $group->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">{{ $group->nama }}</span>
                                                    <small class="text-muted">ID: {{ $group->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex gap-2 justify-content-end">
                                                @php
                                                    $isChecked = in_array($group->id, old('groups', $selectedGroups ?? []));
                                                @endphp
                                                <input type="checkbox"
                                                    name="groups[]"
                                                    id="group"
                                                    value="{{ $group->id }}"
                                                    class="form-check-input"
                                                    {{ $isChecked ? 'checked disabled' : '' }}>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center">
                                                <i class="ti ti-users-off fs-5 text-muted mb-2"></i>
                                                <span class="text-muted">No groups found</span>
                                                @if(request('search'))
                                                <a href="{{ route('group.index') }}" class="btn btn-sm btn-outline-primary mt-3">
                                                    Clear filters
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-calendar-plus me-1"></i> Update
                    </button>
                    <a href="{{ route('waktuLibur.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="ti ti-reload me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function checkAll(source) {
        checkboxes = document.getElementsByName('groups[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>

@endsection