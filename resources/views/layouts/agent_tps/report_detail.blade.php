@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Agent-Tps {{ $agent->nama }}</h5>
    </div>

    <div class="row mt-4">
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bxs-truck"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Korcam</b></p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{$agent->korlurs->korcams->nama}}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-error"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Kor-Tps</b></p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{$agent->korlurs->nama}}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-danger"><i
                                    class="bx bx-git-repo-forked"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Konstituante</b></p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{$jumlahAnggota}}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-time-five"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Terverifikasi</b></p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{$verifikasi}}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            {{-- <a href="{{ route('koordinator/agent/create') }}" type="button" class="btn btn-success">Excel</a> --}}
            <a href="{{ route('report/agent/excel/download', [
                'id' => $agent->id,
                'tps' => $anggota->first()->tps_id ?? 0,
            ]) }}"
                type="button" class="btn btn-success" style="margin-left: 20px;">
                Excel
            </a>
            <a href="{{ route('report/agent/pdf/download', [
                'id' => $agent->id,
                'tps' => $anggota->first()->tps_id ?? 0,
            ]) }}"
                type="button" class="btn btn-danger" target="_blank" id="pdfLink" style="margin-left: 20px;">
                PDF
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>KTP</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Kelurahan / kecamatan</th>
                        <th>TPS</th>
                        <th>Keterangan</th>
                        <th>Status verifikasi</th>
                        {{-- <th>Korlur</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($anggota as $data)
                        <tr>
                            <td>
                                {{ $data->nama }} <br>
                                <small>{{ $data->phone }}</small>
                            </td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->rt }} / {{ $data->rw }}</td>
                            <td>{{ $data->tps->kelurahans->nama_kelurahan }} /
                                {{ $data->tps->kelurahans->kecamatans->nama }}</td>
                            <td>{{ $data->tps->tps }} {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>
                                @if ($data->verified == '1')
                                    <span class="badge bg-label-success me-1">Lolos</span>
                                @elseif($data->verified == '2')
                                    <span class="badge bg-label-danger me-1">Gagal</span>
                                @endif
                            </td>
                            {{-- <td>{{ $data->korlurs_count }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.getElementById('pdfLink').addEventListener('click', function(e) {
            e.preventDefault();
            const pdfUrl = this.getAttribute('href');

            const newWindow = window.open(pdfUrl, '_blank');

            newWindow.addEventListener('load', function() {
                newWindow.print();
            });
        });
        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        }, false);

        // Disable Ctrl + C
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'c' || e.key === 'C')) {
                e.preventDefault();
            }
        });
    </script>
@endsection
