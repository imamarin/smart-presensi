@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Detail Participant</h3>
            <p class="text-muted mb-0">Informasi lengkap participant</p>
        </div>
        <a href="{{ route('participant.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Peserta
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Participant</label>
                        <div class="form-control bg-light">{{ $participant->nama }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Induk</label>
                        <div class="form-control bg-light">{{ $participant->no_induk }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor HP</label>
                        <div class="form-control bg-light">{{ $participant->no_hp ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Grup</label>
                        <div class="form-control bg-light">
                            @forelse ($participant->groupParticipants as $groupParticipant)
                                {{ $groupParticipant->group->nama }} -
                            @empty
                                
                            @endforelse
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dibuat Pada</label>
                        <div class="form-control bg-light">{{ $participant->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-2 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('participant.edit', Crypt::encrypt($participant->id)) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
                <a href="{{ route('participant.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <h3 class="mt-2 mb-2">Data presensi terakhir</h3>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-nowrap">
                            <tr>
                                <th class="ps-4">
                                   ID
                                </th>
                                <th>
                                    Participant
                                </th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status Terlambat</th>
                                <th>Status Check Out</th>
                                <th>Device</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($presensis as $key => $presensi)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $key + 1 }}</td>
                                <td>{{ $presensi->participant->nama ?? "-"}}</td>
                                <td>{{ $presensi->waktu_masuk }}</td>
                                <td>{{ $presensi->waktu_keluar }}</td>
                                                                <td class="text-center">
                                    @if($presensi->status_terlambat)
                                      <span class="badge bg-success">Tepat Waktu</span>
                                    @else
                                      <span class="badge bg-danger">Terlambat</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                  @if ($presensi->status_check_out)
                                    <span class="badge bg-success">Sudah Check Out</span>
                                  @else 
                                    <span class="badge bg-danger">Belum Check Out</span>
                                  @endif
                                </td>
                                <td>{{ $presensi->device->nama }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="ti ti-users-off fs-5 text-muted mb-2"></i>
                                        <span class="text-muted">No presensi found</span>
                                        @if(request('search'))
                                        <a href="{{ route('presensi.index') }}" class="btn btn-sm btn-outline-primary mt-3">
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
    </div>
</div>
@endsection
