@extends('Template.template')

@section('container')

<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">History Presensi</h3>
            <p class="text-muted mb-0">all presensi records in the system</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-3 pb-2">
            <form action="{{ route('presensi.index') }}" method="GET">
            <div class="row g-3">
                    <div class="col-md-3">
                        <select name="group" class="form-select">
                            <option value="">All Group</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                                    {{ $group->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_filter" id="date_filter" class="form-control" placeholder="Filter by date" value="{{ request('date_filter') }}">
                               
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                    </div>
                    <div class="col-md-2">
                        @if(request('group') || request('date_filter'))
                            <a href="{{ route('presensi.index', request()->except('group', 'date_filter')) }}"
                               class="btn btn-danger mt-2" title="Clear search">
                                Clear Filter
                            </a>
                        @endif
                    </div>
                    <div class="col-md-3 text-md-end">
                        <div class="text-muted small">
                            @if($presensis->total() > 0)
                            Showing {{ $presensis->firstItem() }}-{{ $presensis->lastItem() }} of {{ $presensis->total() }}
                            @else
                            No records found
                            @endif
                        </div>
                    </div>
                </form>
        </div>
            </div>


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
                            <th class="text-end pe-4">Actions</th>
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
                                @if(!$presensi->status_terlambat)
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
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('participant.show', Crypt::encrypt($presensi->participant->id)) }}" 
                                    class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                        <i class="ti ti-search"></i> Detail Participant
                                    </a>
                                </div>
                            </td>
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
        
    @if($presensis->hasPages())
        <div class="card-footer bg-transparent border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <p class="small text-muted mb-0">
                        Showing {{ $presensis->firstItem() }} to {{ $presensis->lastItem() }} of {{ $presensis->total() }} entries
                    </p>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{ $presensis->appends(request()->query())->onEachSide(1)->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
@endsection