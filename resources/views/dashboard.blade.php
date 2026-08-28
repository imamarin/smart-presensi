@extends('Template.template')

@section('container')
<style>
    /* Styling to match Login Page (Teal Theme) */
    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .bg-teal-600 {
        background-color: #0d9488 !important;
        color: white !important;
    }
    .text-teal-600 {
        color: #0d9488 !important;
    }
    .badge-soft-success {
        background-color: #d1fae5;
        color: #065f46;
        font-weight: 600;
        padding: 0.5em 0.75em;
    }
    .badge-soft-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        font-weight: 600;
        padding: 0.5em 0.75em;
    }
    .table th {
        background-color: #f0fdfa !important; /* primary-50 */
        color: #0f766e !important; /* primary-700 */
        font-weight: 600;
        border-bottom: 2px solid #ccfbf1; /* primary-100 */
    }
    .table tbody tr:hover {
        background-color: #f0fdfa !important;
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .holiday-card {
        border-left: 4px solid #0d9488;
        background-color: #f0fdfa;
        border-radius: 0.5rem;
    }
</style>

    <!--  Row 1 -->
    <div class="row mb-4 mt-3">
        <div class="col-lg-12">
            <h3 class="fw-bold text-teal-600 mb-4">Dashboard Overview</h3>
            <div class="row">
                <div class="col-lg-4">
                    <!-- Total Participant -->
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="text-muted mb-1 fw-medium">Total Participant</p>
                                    <h3 class="fw-bold mb-0 text-dark">{{ $totalParticipants }}</h3>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div class="icon-shape bg-teal-600 text-white shadow-sm">
                                            <i class="ti ti-tie fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Total Group -->
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="text-muted mb-1 fw-medium">Total Group</p>
                                    <h3 class="fw-bold mb-0 text-dark">{{ $totalGroups }}</h3>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div class="icon-shape bg-teal-600 text-white shadow-sm">
                                            <i class="ti ti-users fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Total Shift -->
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="text-muted mb-1 fw-medium">Total Shift</p>
                                    <h3 class="fw-bold mb-0 text-dark">{{ $totalShifts }}</h3>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div class="icon-shape bg-teal-600 text-white shadow-sm">
                                            <i class="ti ti-sitemap fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Presensi Terakhir -->
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title fw-bold text-teal-600">Presensi Terakhir</h5>
                    <p class="text-muted mb-0 fs-7">Daftar presensi terakhir dari peserta hari ini</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="rounded-start">Participant</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Status Terlambat</th>
                                    <th class="rounded-end">Status Check Out</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse ($presensis as $key => $presensi)
                                <tr>
                                    <td class="fw-medium text-dark">{{ $presensi->participant->nama ?? "-"}}</td>
                                    <td>{{ $presensi->waktu_masuk }}</td>
                                    <td>{{ $presensi->waktu_keluar }}</td>
                                    <td class="text-center">
                                        @if(!$presensi->status_terlambat)
                                            <span class="badge badge-soft-success rounded-pill">Tepat Waktu</span>
                                        @else
                                            <span class="badge badge-soft-danger rounded-pill">Terlambat</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($presensi->status_check_out)
                                            <span class="badge badge-soft-success rounded-pill">Sudah Check Out</span>
                                        @else 
                                            <span class="badge badge-soft-danger rounded-pill">Belum Check Out</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div class="icon-shape bg-light text-muted mb-3" style="width:64px; height:64px;">
                                                <i class="ti ti-users-off fs-7"></i>
                                            </div>
                                            <span class="text-muted fw-medium">Belum ada data presensi</span>
                                            @if(request('search'))
                                            <a href="{{ route('presensi.index') }}" class="btn btn-sm btn-outline-primary mt-3">
                                                Hapus Filter
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

        <!-- Hari Libur -->
        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100 p-2 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title fw-bold text-teal-600">Hari Libur</h5>
                    <p class="text-muted mb-0 fs-7">Daftar hari libur yang akan datang</p>
                </div>
                <div class="card-body pt-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse ($waktuLiburs as $waktuLibur)
                            <div class="holiday-card p-3 shadow-sm">
                                <h6 class="fw-bold text-dark mb-1">{{ $waktuLibur->nama_libur }}</h6>
                                <div class="d-flex align-items-center mt-2">
                                    <i class="ti ti-calendar text-teal-600 me-2"></i>
                                    <span class="badge bg-teal-600">{{ $waktuLibur->tanggal_mulai }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted p-4 border rounded border-dashed">
                                <i class="ti ti-calendar-off fs-6 mb-2 d-block"></i>
                                Tidak ada hari libur dalam waktu dekat
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4 text-center mt-auto">
        <p class="mb-0 text-muted fs-7">Design and Developed by <span class="fw-semibold text-teal-600">SMK YPC TASIKMALAYA</span></p>
    </div>
@endsection
