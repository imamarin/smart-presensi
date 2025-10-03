@extends('Template.template')

@section('container')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Create New Participant</h3>
            <p class="text-muted mb-0">Add a new participant to the system</p>
        </div>
        <div>
            <a href="{{ route('participant.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Participants
            </a>
            <button id="connectBtn" class="btn btn-outline-warning">Connect RFID</button>
        </div>
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
            <form action="{{ route('participant.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- No Induk -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('no_induk') is-invalid @enderror"
                                   id="no_induk"
                                   name="no_induk"
                                   placeholder="No Induk"
                                   value="{{ old('no_induk') }}"
                                   required>
                            <label for="no_induk">No Induk</label>
                            @error('no_induk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   placeholder="Nama"
                                   value="{{ old('nama') }}"
                                   required>
                            <label for="nama">Nama</label>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ID Kartu -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('id_kartu') is-invalid @enderror"
                                   id="id_kartu"
                                   name="id_kartu"
                                   placeholder="ID Kartu"
                                   value="{{ old('id_kartu') }}"
                                   required  autofocus >
                            <label for="id_kartu">ID Kartu</label>
                            @error('id_kartu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ID Fingerprint -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('id_fingerprint') is-invalid @enderror"
                                   id="id_fingerprint"
                                   name="id_fingerprint"
                                   placeholder="ID Fingerprint"
                                   value="{{ old('id_fingerprint') }}"
                                   required  autofocus >
                            <label for="id_fingerprint">ID Fingerprint</label>
                            @error('id_fingerprint')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- No HP -->
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   id="no_hp"
                                   name="no_hp"
                                   placeholder="No HP"
                                   value="{{ old('no_hp') }}"
                                   required>
                            <label for="no_hp">No HP</label>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat"
                                      name="alamat"
                                      placeholder="Alamat"
                                      style="height: 80px"
                                      required>{{ old('alamat') }}</textarea>
                            <label for="alamat">Alamat</label>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-user-plus me-1"></i> Create Participant
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2">
                        <i class="ti ti-reload me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
  let port, reader;
  const input = document.getElementById("id_kartu");
    function formatRFID(raw) {
        return raw
            .match(/.{1,2}/g)
            .map(part => part.toUpperCase())
            .join(":");
    }
  async function connectToRFID(existingPort = null) {
    try {
      port = existingPort || await navigator.serial.requestPort();
      await port.open({ baudRate: 9600 });

      const decoder = new TextDecoderStream();
      const inputDone = port.readable.pipeTo(decoder.writable);
      reader = decoder.readable.getReader();

      let buffer = "";

      while (true) {
        const { value, done } = await reader.read();
        if (done) break;
        if (value) {
          buffer += value;

          if (buffer.includes("\n") || buffer.includes("\r")) {
            const cleaned = buffer.trim();
            const isValidRFID = /^[0-9A-Za-z]{6,20}$/.test(cleaned); 

            if (isValidRFID) {
                const formatted = formatRFID(cleaned);
                input.value = formatted;
                console.log("RFID Valid:", formatted);
            } else {
                console.warn("Data tidak valid:", cleaned);
            }
            buffer = "";
          }
        }
      }
    } catch (err) {
      console.error("Serial Error:", err);
      alert("Gagal akses serial port.");
    }
  }

  // Otomatis connect jika port sudah pernah diotorisasi
  window.addEventListener("load", async () => {
    const ports = await navigator.serial.getPorts();
    if (ports.length > 0) {
      console.log("Port ditemukan sebelumnya. Autoconnecting...");
      await connectToRFID(ports[0]);
    } else {
      console.log("Belum ada port tersimpan. Harus klik tombol.");
    }
  });

  // Tombol fallback kalau belum pernah diotorisasi
  document.getElementById("connectBtn").addEventListener("click", async () => {
    await connectToRFID();
  });
</script>
@endsection
