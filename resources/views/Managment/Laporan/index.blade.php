@extends('Template.template')

@section('container')
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card bg-white shadow-sm" style="border: none; border-radius: 1rem;">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h4 class="card-title fw-bold" style="color: #0d9488;">Laporan Presensi</h4>
                <p class="text-muted fs-7">Filter data presensi berdasarkan grup dan rentang waktu sebelum melakukan export (unduh).</p>
            </div>
            <div class="card-body p-4">
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('laporan.index') }}" method="GET" class="mt-2">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="group_id" class="form-label fw-semibold text-dark">Pilih Group <span class="text-danger">*</span></label>
                            <select name="group_id" id="group_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Group Participant --</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="start_date" class="form-label fw-semibold text-dark">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required value="{{ request('start_date', date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="end_date" class="form-label fw-semibold text-dark">Tanggal Akhir <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <button type="submit" class="btn text-white" style="background-color: #0d9488; border-radius: 0.5rem; padding: 0.5rem 1.5rem;">
                            <i class="ti ti-search me-2"></i> Tampilkan
                        </button>
                        
                        @if(isset($presensis))
                            <a href="{{ route('laporan.export', ['group_id' => request('group_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success" style="border-radius: 0.5rem; padding: 0.5rem 1.5rem;">
                                <i class="ti ti-file-export me-2"></i> Export Laporan
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($presensis))
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card bg-white shadow-sm" style="border: none; border-radius: 1rem;">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="card-title fw-bold" style="color: #0d9488;">Hasil Pencarian Laporan</h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background-color: #f0fdfa;">
                            <tr>
                                <th style="color: #0f766e; border-bottom: 2px solid #ccfbf1;" class="rounded-start">Participant</th>
                                <th style="color: #0f766e; border-bottom: 2px solid #ccfbf1;">Waktu Masuk</th>
                                <th style="color: #0f766e; border-bottom: 2px solid #ccfbf1;">Waktu Keluar</th>
                                <th style="color: #0f766e; border-bottom: 2px solid #ccfbf1;">Status Terlambat</th>
                                <th style="color: #0f766e; border-bottom: 2px solid #ccfbf1;" class="rounded-end">Status Check Out</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($presensis as $presensi)
                            <tr>
                                <td class="fw-medium text-dark">{{ $presensi->participant->nama ?? "-"}}</td>
                                <td>{{ $presensi->waktu_masuk }}</td>
                                <td>{{ $presensi->waktu_keluar }}</td>
                                <td>
                                    @if(!$presensi->status_terlambat)
                                        <span class="badge" style="background-color: #d1fae5; color: #065f46; font-weight: 600; padding: 0.5em 0.75em; border-radius: 50rem;">Tepat Waktu</span>
                                    @else
                                        <span class="badge" style="background-color: #fee2e2; color: #b91c1c; font-weight: 600; padding: 0.5em 0.75em; border-radius: 50rem;">Terlambat</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($presensi->status_check_out)
                                        <span class="badge" style="background-color: #d1fae5; color: #065f46; font-weight: 600; padding: 0.5em 0.75em; border-radius: 50rem;">Sudah Check Out</span>
                                    @else 
                                        <span class="badge" style="background-color: #fee2e2; color: #b91c1c; font-weight: 600; padding: 0.5em 0.75em; border-radius: 50rem;">Belum Check Out</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="ti ti-file-off fs-5 text-muted mb-2"></i>
                                        <span class="text-muted fw-medium">Tidak ada data presensi pada rentang tanggal tersebut.</span>
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
@endif

@endsection
