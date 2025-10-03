@extends('Template.template')

@section('container')
    <!--  Row 1 -->
        <div class="row">
        {{--  <div class="col-lg-8 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Sales Overview</h5>
                  </div>
                  <div>
                    <select class="form-select">
                      <option value="1">March 2023</option>
                      <option value="2">April 2023</option>
                      <option value="3">May 2023</option>
                      <option value="4">June 2023</option>
                    </select>
                  </div>
                </div>
                <div id="chart"></div>
              </div>
            </div>
          </div> --}}
          <div class="col-lg-12">
            <div class="row">

              <div class="col-lg-4">
                <!-- Monthly Earnings -->
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold"> Total Participant </h5>
                        <h4 class="fw-semibold mb-3">{{ $totalParticipants }}</h4>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div
                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                            <i class="ti ti-tie fs-6"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <!-- Monthly Earnings -->
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold"> Total Group </h5>
                        <h4 class="fw-semibold mb-3">{{ $totalGroups }}</h4>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div
                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                            <i class="ti ti-users fs-6"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-lg-4">
                <!-- Monthly Earnings -->
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold"> Total Shift </h5>
                        <h4 class="fw-semibold mb-3">{{ $totalShifts }}</h4>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div
                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                            <i class="ti ti-sitemap fs-6"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
            <div class="card-header">
                <h5 class="card-title
                fw-semibold">Presensi Terakhir</h5>
                <p class="text-muted
                mb-0">Daftar presensi terakhir dari peserta</p>
            </div>
              <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-nowrap">
                            <tr>
                                <th>
                                    Participant
                                </th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status Terlambat</th>
                                <th>Status Check Out</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($presensis as $key => $presensi)
                            <tr>
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
            </div>
          </div>
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100 p-3">
              <div class="card-header bg-white">
                <h5 class="card-title">
                  Hari Libur
                </h5>
                <p class="text-muted mb-0">
                  Daftar hari libur yang telah ditentukan
                </p>
              </div>
              <div class="card-body">
                <div class="card p-2">
                  <div class="d-flex flex-column gap-3">
                    @forelse ($waktuLiburs as $waktuLibur)
                      <div class="d-flex flex-column p-2">
                        <h4 class="py-2">{{ $waktuLibur->nama_libur }}</h4>
                        <p class="badge bg-danger">{{ $waktuLibur->tanggal_mulai }}</p>
                        <hr>
                      </div>
                    @empty
                      <div class="text-center text-muted">Tidak ada hari libur yang ditentukan</div>
                    @endforelse
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by SMK YPC TASIKMALAYA</a></p>
        </div>
@endsection
