@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">User Management</h3>
            <p class="text-muted mb-0">Manage all registered users in the system</p>
        </div>
        @if (Auth::user()->level === 'admin')
            <a href="{{ route('user.create') }}" class="btn btn-success d-flex align-items-center">
                <i class="ti ti-plus me-1"></i> Add New User
            </a>
        @endif
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-3 pb-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('user.index') }}" method="GET">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-transparent">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                   placeholder="Search users..." value="{{ request('search') }}">
                            @if(request('search'))
                            <a href="{{ route('user.index', request()->except('search')) }}"
                               class="input-group-text bg-transparent text-danger" title="Clear search">
                                <i class="ti ti-x"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('user.index') }}" method="GET">
                        <select name="level" class="form-select" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ request('level') == 'operator' ? 'selected' : '' }}>Operator</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-3 text-md-end">
                    <div class="text-muted small">
                        @if($users->total() > 0)
                        Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }}
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
                                <a href="{{ route('user.index', [
                                    'sort' => 'id',
                                    'direction' => request('sort') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'level' => request('level')
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
                                <a href="{{ route('user.index', [
                                    'sort' => 'name',
                                    'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'level' => request('level')
                                ]) }}" class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                    <span>User</span>
                                    @if(request('sort') == 'name')
                                    <i class="ti ti-arrows-sort fs-4 text-primary"></i>
                                    <i class="ti ti-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} fs-4 text-primary"></i>
                                    @else
                                    <i class="ti ti-arrows-sort fs-4 text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Email</th>
                            <th>
                                <a href="{{ route('user.index', [
                                    'sort' => 'level',
                                    'direction' => request('sort') == 'level' && request('direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'level' => request('level')
                                ]) }}" class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                    <span>Role</span>
                                    @if(request('sort') == 'level')
                                    <i class="ti ti-arrows-sort fs-4 text-primary"></i>
                                    <i class="ti ti-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} fs-4 text-primary"></i>
                                    @else
                                    <i class="ti ti-arrows-sort fs-4 text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            @if (Auth::user()->level === 'admin')                                
                                <th class="text-end pe-4">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($users as $key => $user)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $key +1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $user->name }}</span>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block">{{ $user->email }}</span>
                                <small class="text-muted">Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</small>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $user->level == 'admin' ? 'primary' : 'success' }}-subtle text-{{ $user->level == 'admin' ? 'primary' : 'success' }}">
                                    <i class="ti ti-{{ $user->level == 'admin' ? 'shield' : 'user' }} me-1"></i>
                                    {{ ucfirst($user->level) }}
                                </span>
                            </td>
                            @if (Auth::user()->level === 'admin')
                                
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('user.show', Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-icon btn-outline-info rounded-3" data-bs-toggle="tooltip" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('user.edit', Crypt::encrypt($user->id)) }}" class="btn btn-sm btn-icon btn-outline-primary rounded-3" data-bs-toggle="tooltip" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('user.destroy', Crypt::encrypt($user->id)) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger rounded-3"
                                                    data-bs-toggle="tooltip" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="ti ti-users-off fs-5 text-muted mb-2"></i>
                                    <span class="text-muted">No users found</span>
                                    @if(request('search') || request('level'))
                                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-primary mt-3">
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

        @if($users->hasPages())
        <div class="card-footer bg-transparent border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <p class="small text-muted mb-0">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </p>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{ $users->appends(request()->query())->onEachSide(1)->links() }}
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add active class to sorted column header
        const sortParam = '{{ request("sort") }}';
        if (sortParam) {
            document.querySelectorAll(`th a[href*="sort=${sortParam}"]`).forEach(el => {
                el.classList.add('active-sort');
            });
        }
    });
</script>
@endsection
