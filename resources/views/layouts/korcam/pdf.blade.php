<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #000; /* Add a border style here */
        padding: 8px;
        text-align: left;
    }
</style>
    <div class="card text-center">
        <h2 class="card-header" style="text-align: center">Koordinator Kecamatan {{$korcam->nama}}</h2>
    </div>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            {{-- <a href="{{ route('korcam/create') }}" type="button" class="btn btn-primary ">Tambah</a> --}}
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Korlur</th>
                        <th>Tim</th>
                        <th>phone</th>
                        <th>KTP</th>
                        <th>TPS</th>
                        <th>Keterangan</th>
                        <th>Konstituen</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($korlur as $data)
                        <tr>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->partais->nama }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->tps->tps }} - {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>{{ $data->anggota_count }}</td>
                            {{-- <td>{{ $data->korlurs_count }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
