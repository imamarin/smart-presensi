@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Detail Jam Kerja</h3>
            <p class="text-muted mb-0">Informasi lengkap jam kerja</p>
        </div>
        <a href="{{ route('jamKerja.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Jam Kerja
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jam Kerja</label>
                        <div class="form-control bg-light">{{ $jamKerja->nama }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Masuk</label>
                        <div class="form-control bg-light">{{ $jamKerja->jam_masuk }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Pulang</label>
                        <div class="form-control bg-light">{{ $jamKerja->jam_pulang }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mulai Scan Masuk</label>
                        <div class="form-control bg-light">{{ $jamKerja->jam_mulai_scan_masuk }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mulai Scan Keluar</label>
                        <div class="form-control bg-light">{{ $jamKerja->jam_mulai_scan_keluar }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Toleransi Check Out (Jam)</label>
                        <div class="form-control bg-light">{{ $jamKerja->toleransi_check_out ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Toleransi Keterlambatan Masuk (menit)</label>
                        <div class="form-control bg-light">{{ $jamKerja->toleransi_terlambat ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Toleransi Keterlambatan Pulang (menit)</label>
                        <div class="form-control bg-light">{{ $jamKerja->toleransi_pulang_cepat ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dibuat Pada</label>
                        <div class="form-control bg-light">{{ $jamKerja->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-2 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('jamKerja.edit', Crypt::encrypt($jamKerja->id)) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
                <a href="{{ route('jamKerja.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
