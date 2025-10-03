@extends('Template.template')

@section('container')
<div class="container mt-4">
    <h3 class="mb-4">Create Detail Shift</h3>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('detailShift.store') }}" method="POST">
        @csrf
        <input type="hidden" name="shift_id" value="{{ Crypt::encrypt($shift->id) }}">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-transparent border-0 pt-3 pb-2">

            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-nowrap">
                            <tr>
                                <th>Hari</th>
                                <th class="text-end pe-4">
                                    <input type="checkbox" name="all_checked" id="all_checked" class="form-check-input"
                                        onclick="toggleAllCheckboxes(this)">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <tr>
                                <td class="ps-4 fw-semibold">Senin</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Senin"
                                           @if(in_array('Senin', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4 fw-semibold">Selasa</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Selasa"
                                           @if(in_array('Selasa', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>
                                                        <tr>
                                <td class="ps-4 fw-semibold">Rabu</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Rabu"
                                           @if(in_array('Rabu', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4 fw-semibold">Kamis</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Kamis"
                                           @if(in_array('Kamis', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4 fw-semibold">Jumat</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Jumat"
                                           @if(in_array('Jumat', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4 fw-semibold">Sabtu</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Sabtu"
                                           @if(in_array('Sabtu', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4 fw-semibold">Minggu</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           value="Minggu"
                                           @if(in_array('Minggu', $detailShiftHari ?? []))
                                                checked disabled
                                            {{-- Don't include name so it won't be submitted --}}
                                            @else
                                                name="days[]"
                                            @endif
                                    >
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('shift.show', Crypt::encrypt($shift->id)) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
        function toggleAllCheckboxes(source) {
        if (!source.checked) {
            document.getElementById('all_checked').checked = false;

        }
        const checkboxes = document.querySelectorAll('input[name="days[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
    }
</script>
@endsection
