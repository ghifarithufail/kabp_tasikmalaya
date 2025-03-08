
    <div class="card text-center">
        <h5 class="card-header">Koordinator Tps</h5>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tim</th>
                        <th>KTP</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Kelurahan</th>
                        <th>Tps</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Agent tps</th>
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
                            <td>'{{ $data->nik }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->rt }} / {{ $data->rw }}</td>
                            <td>{{ $data->kelurahans->nama_kelurahan }}</td>
                            <td>{{ $data->tps->tps }} {{ $data->kelurahans->nama_kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>
                                @if ($data->status == '1')
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                @elseif($data->status == '2')
                                    <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{ $data->agent_tps_data_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
