@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Detail Jadwal Participant</h3>
            <p class="text-muted mb-0">Informasi lengkap jadwal participant</p>
        </div>
        <a href="{{ route('jadwalParticipant.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Jadwal
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form>
                <div class="row g-3">
                    <!-- Nama Peserta -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control"
                                   id="participant"
                                   value="{{ $shift->nama ?? '-' }}"
                                   readonly>
                            <label for="participant">Shift</label>
                        </div>
                    </div>



                    <!-- Nama Jadwal -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control"
                                   id="jadwal"
                                   value="{{ $shift->tanggal_mulai ?? '-' }}"
                                   readonly>
                            <label for="jadwal">Tanggal Mulai Shift</label>
                        </div>
                    </div>

                    <!-- jam_masuk -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control"
                                   id="jam_masuk"
                                   value="{{ $shift->jamKerja->jam_masuk ?? '-' }}"
                                   readonly>
                            <label for="jam_masuk">Jam Masuk</label>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control"
                                   id="jam_pulang"
                                   value="{{ $shift->jamKerja->jam_pulang ?? '-' }}"
                                   readonly>
                            <label for="jam_pulang">Jam Pulang</label>
                        </div>
                    </div>
                </div>
                <form>
                    @csrf
                    {{-- @method('DELETE') --}}
                    <input type="hidden" name="id_shift" value="{{ $shift->id }}">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-nowrap">
                                    <tr>
                                        <th class="ps-4">
                                            <a href="{{ route('participant.index', [
                                                'sort' => 'id',
                                                'direction' => request('sort') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc',
                                                'search' => request('search'),
                                            ]) }}" class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                                <span>ID</span>
                                                @if(request('sort') == 'id')
                                                <i class="ti ti-arrows-sort fs-4 text-primary"></i>
                                                <i class="ti ti-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} fs-4 text-primary"></i>
                                                @else
                                                <i class="ti ti-arrows-sort fs-4 text-muted opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('participant.index', [
                                                'sort' => 'nama',
                                                'direction' => request('sort') == 'nama' && request('direction') == 'asc' ? 'desc' : 'asc',
                                                'search' => request('search'),
                                            ]) }}" class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                                <span>Nama</span>
                                                @if(request('sort') == 'nama')
                                                <i class="ti ti-arrows-sort fs-4 text-primary"></i>
                                                <i class="ti ti-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} fs-4 text-primary"></i>
                                                @else
                                                <i class="ti ti-arrows-sort fs-4 text-muted opacity-50"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>No Induk</th>
                                        <th>ID Kartu</th>
                                        <th>No HP</th>
                                        <th>Alamat</th>
                                        <th>Status Jadwal</th>
                                       
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse ($participants as $key => $participant)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">{{ $participant->nama }}</span>
                                                    <small class="text-muted">ID: {{ $participant->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $participant->no_induk }}</td>
                                        <td>{{ $participant->id_kartu }}</td>
                                        <td>{{ $participant->no_hp }}</td>
                                        <td>{{ $participant->alamat }}</td>
                                        <td>
                                            @if($participant->jadwalParticipant)
                                            <span class="badge bg-success">Jadwal {{ $participant->jadwalParticipant->shift->nama }}</span>
                                            @else
                                            <span class="badge bg-secondary">Belum Dijadwalkan</span>
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center">
                                                <i class="ti ti-users-off fs-5 text-muted mb-2"></i>
                                                <span class="text-muted">No participants found</span>
                                                @if(request('search'))
                                                <a href="{{ route('participant.index') }}" class="btn btn-sm btn-outline-primary mt-3">
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
                </form>

            </form>
        </div>
    </div>
</div>
@endsection