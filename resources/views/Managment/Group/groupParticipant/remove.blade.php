@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Remove Participants to Group</h3>
            <p class="text-muted mb-0">Select participants to remove to this group</p>
        </div>
        <a href="{{ route('group.show', Crypt::encrypt($group->id)) }}" class="btn btn-outline-secondary" onclick="cancelInputParticipant()">
            <i class="ti ti-arrow-left me-1"></i> Back to Group List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form id="participant-form" action="{{ route('groupParticipant.destroy') }}" method="POST">
                @csrf
                @method('DELETE');
                <input type="hidden" name="selected_participants" id="selected_participants">
                <input type="hidden" name="group_id" value="{{ $group->id }}">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>No Induk</th>
                                <th>Nama</th>
                                <th>ID Kartu</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th class="text-end pe-4">
                                    <input type="checkbox" name="all_checked" id="all_checked" class="form-check-input"
                                        onclick="toggleAllCheckboxes(this)">
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participants as $participant)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $participant->no_induk }}</td>
                                    <td>{{ $participant->nama }}</td>
                                    <td>{{ $participant->id_kartu }}</td>
                                    <td>{{ $participant->no_hp }}</td>
                                    <td>{{ $participant->alamat }}</td>
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
                                    <td colspan="7" class="text-center text-muted">No participants found.</td>
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

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-users-plus me-1"></i> Remove Group Participants
                    </button>
                    <a href="{{ route('group.show', Crypt::encrypt($group->id)) }}" class="btn btn-outline-secondary ms-2" onclick="cancelInputParticipant()">
                        <i class="ti ti-reload me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
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
