@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Detail Device</h3>
            <p class="text-muted mb-0">Informasi lengkap perangkat</p>
        </div>
        <a href="{{ route('device.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Device
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Device</label>
                        <div class="form-control bg-light">{{ $device->nama }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Device ID</label>
                        <div class="form-control bg-light">{{ $device->device_id }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <div>
                            @if($device->status == 'active')
                                <span class="badge bg-success px-3 py-2">Active</span>
                            @else
                                <span class="badge bg-danger px-3 py-2">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <div class="form-control bg-light">{{ $device->lokasi ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">API KEY <span class="text-danger">(SUPER SECRET)</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="api-key" value="••••••••••••••••••••••••••" readonly>
                            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#confirmPasswordModal">
                                Tampilkan
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Koneksi</label>
                        @if($device->status_koneksi_badge == 'Connect')
                            <span class="badge bg-success px-3 py-2 d-inline ms-2">Connect</span>
                        @else
                            <span class="badge bg-danger px-3 py-2 d-inline ms-2">Disconnect</span>
                        @endif

                        <div>
                            <span class="form-control bg-light">Terakhir aktif : {{ $device->status_koneksi }}</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="mt-4 pt-2 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('device.edit', Crypt::encrypt($device->id)) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
                <a href="{{ route('device.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- Modal Verifikasi Password -->
<div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="confirmPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="verifyPasswordForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Verifikasi Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="password" class="form-label">Masukkan password Anda</label>
            <input type="password" class="form-control" name="password" id="password" required>
            <div class="invalid-feedback" id="passwordError">Password salah</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Verifikasi</button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>

<script>
    const form = document.getElementById('verifyPasswordForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const deviceId = {{ $device->id }};

        fetch("{{ route('verify.password') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ password: password, device_id: deviceId })
        })
        .then(res => {
            if (res.status === 401) throw new Error('Unauthorized');
            return res.json();
        })
        .then(data => {
            document.getElementById('api-key').value = data.api_key;
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmPasswordModal'));
            modal.hide();
        })
        .catch(() => {
            const error = document.getElementById('passwordError');
            error.style.display = 'block';
            setTimeout(() => error.style.display = 'none', 3000);
        });
    });
</script>

@endsection