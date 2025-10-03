@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">My Profile</h3>
            <p class="text-muted mb-0">Informasi lengkap user</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama User</label>
                        <div class="form-control bg-light">{{ $user->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <div class="form-control bg-light">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Role</label>
                        <div>
                            @if($user->level == 'admin')
                                <span class="badge bg-primary px-3 py-2">Admin</span>
                            @elseif($user->level == 'operator')
                                <span class="badge bg-info px-3 py-2 text-dark">Operator</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">{{ ucfirst($user->level) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dibuat Pada</label>
                        <div class="form-control bg-light">{{ $user->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-2 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('me.update') }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
