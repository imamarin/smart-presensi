@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Remove Participant from Jadwal Participant</h3>
            <p class="text-muted mb-0">Pilih Participant yang akan hapus dari jadwal.</p>
        </div>
        <a href="{{ route('jadwalParticipant.index') }}" class="btn btn-outline-secondary" onclick="cancelInputParticipant()">
            <i class="ti ti-arrow-left me-1"></i> Back to Jadwal Participant
        </a>
    </div>
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-3 pb-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('jadwalParticipant.create', $shift->id) }}" method="GET">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-transparent">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Search participants..." value="{{ request('search') }}">
                            @if(request('search'))
                            <a href="{{ route('jadwalParticipant.create', [
                                'id_shift' => $shift->id,
                                'filter_grup' => request('filter_grup'),
                                'sort' => request('sort'),
                                'direction' => request('direction'),
                                'search' => null,
                            ]) }}"
                            class="input-group-text bg-transparent text-danger" title="Clear search">
                                <i class="ti ti-x"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- <div class="col-md-3 text-md-end">
                    <div class="text-muted small">
                        @if($participants->total() > 0)
                        Showing {{ $participants->firstItem() }}-{{ $participants->lastItem() }} of {{ $participants->total() }}
                        @else
                        No records found
                        @endif
                    </div>
                </div> --}}
            </div>
        </div>

        <form id="participant-form" action="{{ route('jadwalParticipant.destroyItem', $shift->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_participants" id="selected_participants">
            <input type="hidden" name="id_shift" value="{{ $shift->id }}">
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
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Status Jadwal</th>
                                <th class="text-end pe-4">
                                    <input type="checkbox" name="all_checked" id="all_checked" class="form-check-input"
                                        onclick="toggleAllCheckboxes(this)">
                                </th>
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
                                <td>{{ $participant->no_hp }}</td>
                                <td>{{ $participant->alamat }}</td>
                                <td>
                                    @if($participant->jadwalParticipant)
                                    <span class="badge bg-success">Jadwal {{ $participant->jadwalParticipant->shift->nama }}</span>
                                    @else
                                    <span class="badge bg-secondary">Belum Dijadwalkan</span>
                                    @endif
                                </td>
                                </td>
                                    <td class="text-center">
                                    <input type="checkbox"
                                        class="participant-checkbox"
                                        id="participant"
                                        value="{{ $participant->id }}"
                                        @if(in_array($participant->id, $groupParticipantIds ?? []))
                                            checked disabled
                                        @endif
                                    >

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
            </div>
            <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="ti ti-users-plus me-1"></i> Remove Group Participants
                    </button>
                    <a href="{{ route('jadwalParticipant.index') }}" class="btn btn-outline-secondary ms-2" onclick="cancelInputParticipant()">
                        <i class="ti ti-reload me-1"></i> Cancel
                    </a>
                </div>
        </form>
        
    </div>
</div>

<script>
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) checkbox.checked = source.checked;
        });
        saveSelectedToLocalStorage(); // Simpan ke localStorage juga
    }

    const STORAGE_KEY = 'selectedParticipants';

    function saveSelectedToLocalStorage() {
        const existing = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const current = Array.from(document.querySelectorAll('.participant-checkbox'))
            .filter(cb => cb.checked && !cb.disabled)
            .map(cb => cb.value);

        // Hapus ID yang tampil di halaman sekarang dari existing
        const currentPageIds = Array.from(document.querySelectorAll('.participant-checkbox'))
            .map(cb => cb.value);

        const filtered = existing.filter(id => !currentPageIds.includes(id));

        // Gabungkan data dari localStorage lama (kecuali yang di halaman sekarang) + baru
        const merged = [...new Set([...filtered, ...current])];

        localStorage.setItem(STORAGE_KEY, JSON.stringify(merged));
    }

    function restoreCheckboxesFromLocalStorage() {
        const selected = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        console.log('Restore:', selected); // Debug

        selected.forEach(id => {
            const checkbox = document.querySelector(`.participant-checkbox[value="${id}"]`);
            if (checkbox && !checkbox.disabled) checkbox.checked = true;
        });
    }

    function updateHiddenInputBeforeSubmit() {
        const selected = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        console.log('Selected before submit:', selected); // Debug
        document.getElementById('selected_participants').value = selected.join(',');
    }

    document.addEventListener('DOMContentLoaded', () => {
        restoreCheckboxesFromLocalStorage();

        document.querySelectorAll('.participant-checkbox').forEach(cb => {
            cb.addEventListener('change', saveSelectedToLocalStorage);
        });

        const form = document.getElementById('participant-form');
        form.addEventListener('submit', function (e) {
            updateHiddenInputBeforeSubmit(); // Pastiin dipanggil
            localStorage.removeItem(STORAGE_KEY);
        });
    });

    function cancelInputParticipant() {
        localStorage.removeItem(STORAGE_KEY);
    }

</script>

@endsection