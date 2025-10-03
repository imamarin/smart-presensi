@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Pilih Shift untuk Jadwal Participant</h3>
            <p class="text-muted mb-0">Pilih shift yang akan diatur untuk participant ini.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nama Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($shifts as $key => $shift)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $key + 1 }}</td>
                            <td>{{ $shift->nama }}</td>
                            <td>{{ $shift->jamKerja->jam_masuk }}</td>
                            <td>{{ $shift->jamKerja->jam_pulang }}</td>
                            {{-- <td class="text-end pe-4">
                                <a href="{{ route('jadwalParticipant.pilih', ['shift' => $shift->id]) }}" 
                                   class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                    <i class="ti ti-user-plus"></i> Pilih Participant
                                </a>
                            </td> --}}
                            <td class="text-end pe-4">
                                <a href="{{ route('jadwalParticipant.create', Crypt::encrypt($shift->id)) }}" 
                                   class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                    <i class="ti ti-user-plus"></i> Pilih Participant
                                </a>
                                <a href="{{ route('jadwalParticipant.remove', Crypt::encrypt($shift->id)) }}" 
                                   class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
                                    <i class="ti ti-user-minus"></i> Remove Participant
                                </a>
                                <a href="{{ route('jadwalParticipant.show', Crypt::encrypt($shift->id)) }}" 
                                   class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                    <i class="ti ti-search"></i> Detail Participant
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-calendar-x fs-5 text-muted mb-2"></i>
                                    <span class="text-muted">Tidak ada shift tersedia</span>
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
@endsection