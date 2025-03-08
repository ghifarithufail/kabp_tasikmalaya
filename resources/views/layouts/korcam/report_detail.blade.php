@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Koordinator Kecamatan {{ $korcam->nama }}</h5>
    </div>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            <a href="{{ route('report/korcam/excel', $korcam->id) }}" type="button" class="btn btn-success">Excel</a>
            <a href="{{ route('report/korcam/pdf', $korcam->id) }}" type="button" class="btn btn-danger" target="_blank"
                id="pdfLink" style="margin-left: 20px;">
                PDF
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Partai</th>
                        <th>KTP</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Kelurahan / kecamatan</th>
                        <th>Tps</th>
                        <th>Keterangan</th>
                        <th>Konstituen</th>
                        <th>Status</th>
                        {{-- <th>Korlur</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($korlur as $data)
                        <tr>
                            <td>
                                {{ $data->nama }} <br>
                                <small>{{ $data->phone }}</small>
                            </td>
                            <td>{{ $data->partais->nama }}</td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->rt }} / {{ $data->rw }}</td>
                            <td>{{ $data->kelurahans->nama_kelurahan }} / {{ $data->kelurahans->kecamatans->nama }}</td>
                            <td>{{ $data->tps->tps }} - {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>{{ $data->anggota_count }}</td>
                            <td>
                                @if ($data->status == '1')
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                @elseif($data->status == '2')
                                    <span class="badge bg-label-danger me-1">Tidak Aktif</span>
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
