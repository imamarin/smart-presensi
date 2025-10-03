@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Import Participant From Excel File</h3>
            <p class="text-muted mb-0">import data from excel file</p>
        </div>
       <a href="{{ route('participant.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Peserta
        </a>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-3 pb-2">
            <h3>Contoh Penamaan Dalam Excel</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <td>no_induk</td>
                            <td>nama</td>
                            <td>id_kartu</td>
                            <td>id_fingerprint</td>
                            <td>no_hp</td>
                            <td>alamat</td>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <tr>
                            <td>ID010203</td>
                            <td>Example</td>
                            <td>CARD0212</td>
                            <td>0212</td>
                            <td>0987*******</td>
                            <td>Example</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            <h3 class="my-4">Import File Excel</h3>
            <form action="{{ route('participant.import.data') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 m-2 ">
                    <!-- No Induk -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="file"
                                   class="form-control"
                                   id="file"
                                   name="file"
                                   placeholder="No Induk"
                                   value="{{ old('file') }}"
                                    accept=".xlsx, .csv"
                                   required>
                            <label for="file">File data participant</label>
                        </div>
                    </div>
                    {{-- <div class="col-md-6"> --}}
                        {{-- <div class="form-floating"> --}}
                            <button type="submit" class="btn btn-primary col-md-6 form-floating">
                                <i class="ti ti-user-plus me-1"></i> Import Participant
                            </button>
                        {{-- </div> --}}
                    {{-- </div> --}}

                </div>

            </form>
        </div>
    </div>

</div>
@endsection
