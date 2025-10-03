@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Jam Kerja Management</h3>
            <p class="text-muted mb-0">Manage all work time schedules in the system</p>
        </div>
        <a href="{{ route('jamKerja.create') }}" class="btn btn-success d-flex align-items-center">
            <i class="ti ti-plus me-1"></i> Add New Jam Kerja
        </a>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-3 pb-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('jamKerja.index') }}" method="GET">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-transparent">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                   placeholder="Search jam kerja..." value="{{ request('search') }}">
                            @if(request('search'))
                            <a href="{{ route('jamKerja.index', request()->except('search')) }}"
                               class="input-group-text bg-transparent text-danger" title="Clear search">
                                <i class="ti ti-x"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="text-muted small">
                        @if($jamKerjas->total() > 0)
                        Showing {{ $jamKerjas->firstItem() }}-{{ $jamKerjas->lastItem() }} of {{ $jamKerjas->total() }}
                        @else
                        No records found
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th class="ps-4">
                                <a href="{{ route('jamKerja.index', [
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
                                <a href="{{ route('jamKerja.index', [
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
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($jamKerjas as $key => $jamKerja)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $jamKerja->nama }}</span>
                                        <small class="text-muted">ID: {{ $jamKerja->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $jamKerja->jam_masuk }}</td>
                            <td>{{ $jamKerja->jam_pulang }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('jamKerja.show', Crypt::encrypt($jamKerja->id)) }}" class="btn btn-sm btn-icon btn-outline-info rounded-3" data-bs-toggle="tooltip" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('jamKerja.edit', Crypt::encrypt($jamKerja->id)) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-3" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('jamKerja.destroy', Crypt::encrypt($jamKerja->id)) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-3"
                                                data-bs-toggle="tooltip" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this jam kerja?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-users-off fs-5 text-muted mb-2"></i>
                                    <span class="text-muted">No jam kerja found</span>
                                    @if(request('search'))
                                    <a href="{{ route('jamKerja.index') }}" class="btn btn-sm btn-outline-primary mt-3">
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
    @if($jamKerjas->hasPages())
        <div class="card-footer bg-transparent border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <p class="small text-muted mb-0">
                        Showing {{ $jamKerjas->firstItem() }} to {{ $jamKerjas->lastItem() }} of {{ $jamKerjas->total() }} entries
                    </p>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{ $jamKerjas->appends(request()->query())->onEachSide(1)->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    @endif
    </div>
</div>
@endsection
