<table class="table table-hover" style="zoom: 0.75">
    <thead>
        <tr>
            <th>Nama</th>
            <th>KTP</th>
            <th>Alamat</th>
            <th>RT/RW</th>
            <th>kelurahan</th>
            <th>Korlur</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Tps</th>
            <th>Konstituen</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach ($agent as $data)
                <tr>
                    <td>
                            {{ $data->nama_koordinator }} <br>
                            <small>{{ $data->phone }}</small>
                    </td>                                
                    <td>'{{ $data->nik }}</td>
                    <td>{{ $data->alamat }}</td>
                    <td>{{ $data->rt }} / {{ $data->rw }}</td>
                    <td>{{ $data->nama_kelurahan }} </td>
                    <td>{{ $data->nama_korhan }}</td>
                    <td>{{ $data->keterangan }}</td>
                    <td>
                        @if ($data->status == '1')
                            <span class="badge bg-label-success me-1">Aktif</span>
                        @elseif($data->status == '2')
                            <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>{{ $data->tps }} {{ $data->nama_kelurahan }}</td>
                    <td>{{ $data->belum }}</td>
                </tr>
        @endforeach
    </tbody>
</table>