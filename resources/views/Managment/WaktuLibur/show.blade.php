{{-- filepath: d:\zall\project_presensi\resources\views\Managment\WaktuLibur\create.blade.php --}}
@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Detail Waktu Libur</h3>
            <p class="text-muted mb-0">detail hari libur sistem</p>
        </div>
        <a href="{{ route('waktuLibur.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Libur
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- <form > --}}
                <div class="row g-3">
                    <!-- Nama Libur -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama_libur') is-invalid @enderror"
                                   id="nama_libur"
                                   name="nama_libur"
                                   placeholder="Contoh: Hari Raya"
                                   value="{{ $waktuLibur->nama_libur }}"
                                   disabled>
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
                                   value="{{ $waktuLibur->tanggal_mulai }}"
                                   disabled>

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
                                   value="{{ $waktuLibur->tanggal_akhir }}"
                                   disabled>
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
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse ($groups as $key => $group)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $key + 1 }}</td>
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
                                                <form action="{{ route('groupLibur.destroyItem', ['id_group' => Crypt::encrypt($group->id), 'id_waktu_libur' => Crypt::encrypt($waktuLibur->id)]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-3"
                                                            data-bs-toggle="tooltip" title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this waktu libur?')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
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
                    {{-- Pagination --}}
                    {{-- <div class="d-flex justify-content-end mt-3">
                        {{ $groups->links() }}
                    </div> --}}
                </div>
            {{-- </form> --}}
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
