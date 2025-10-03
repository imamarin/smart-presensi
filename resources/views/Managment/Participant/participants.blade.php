@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Participant Management</h3>
            <p class="text-muted mb-0">Manage all registered participant in the system</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('participant.create') }}" class="btn btn-success d-flex align-items-center">
                <i class="ti ti-plus me-1"></i> Add New Participant
            </a>
            <a href="{{ route('export.participant') }}" class="btn btn-success d-flex align-items-center">
                <i class="ti ti-file-spreadsheet me-1"></i> Export Participant
            </a>
            <a href="{{ route('participant.import') }}" class="btn btn-success d-flex align-items-center">
                <i class="ti ti-file-spreadsheet me-1"></i> Import Participant
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-3 pb-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('participant.index') }}" method="GET">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-transparent">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Search participants..." value="{{ request('search') }}">
                            @if(request('search'))
                            <a href="{{ route('participant.index', request()->except('search')) }}"
                            class="input-group-text bg-transparent text-danger" title="Clear search">
                                <i class="ti ti-x"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="col-md-3 text-md-end">
                    <div class="text-muted small">
                        @if($participants->total() > 0)
                        Showing {{ $participants->firstItem() }}-{{ $participants->lastItem() }} of {{ $participants->total() }}
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
                            <th>ID Fingerprint</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th class="text-end pe-4">Actions</th>
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
                            <td>{{ $participant->id_fingerprint }}</td>
                            <td>{{ $participant->no_hp }}</td>
                            <td>{{ $participant->alamat }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('participant.show', Crypt::encrypt($participant->id)) }}" class="btn btn-sm btn-icon btn-outline-info rounded-3" data-bs-toggle="tooltip" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('participant.edit', Crypt::encrypt($participant->id)) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-3" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('participant.destroy', Crypt::encrypt($participant->id)) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-3"
                                                data-bs-toggle="tooltip" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this participant?')">
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
    </div>

    @if($participants->hasPages())
        <div class="card-footer bg-transparent border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <p class="small text-muted mb-0">
                        Showing {{ $participants->firstItem() }} to {{ $participants->lastItem() }} of {{ $participants->total() }} entries
                    </p>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{ $participants->appends(request()->query())->onEachSide(1)->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
@endsection
