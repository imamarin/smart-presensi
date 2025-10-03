@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Shift Detail</h3>
            <p class="text-muted mb-0">view shift detail</p>
        </div>
        <a href="{{ route('shift.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Shift
        </a>
    </div>
    <form>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Shift</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $shift->nama) }}" disabled>
        </div>
    </form>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Detail Shift</h3>
            <p class="text-muted mb-0">Manage all detail shift in the system</p>
        </div>
        <a href="{{ route('shift.createDetailShift', Crypt::encrypt($shift->id)) }}" class="btn btn-success d-flex align-items-center">
            <i class="ti ti-plus me-1"></i> Add detail shift
        </a>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th class="ps-4">
                                ID
                            </th>
                            <th>
                                Hari
                            </th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($shift->detailShifts as $key => $detailShift)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $key + 1 }}</td>
                            <td>{{ $detailShift->hari }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <form action="{{ route('detailShift.destroy', $detailShift->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="group_id" value="{{ $shift->id }}">
                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-3"
                                                data-bs-toggle="tooltip" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this detailShift?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-users-off fs-5 text-muted mb-2"></i>
                                    <span class="text-muted">No shift found</span>
                                    @if(request('search'))
                                    <a href="{{ route('shift.createDetailShift') }}" class="btn btn-sm btn-outline-primary mt-3">
                                        Create Detail Shift
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
@endsection
