<table class="table table-hover" style="zoom: 0.75">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>KTP</th>
            <th>Agen</th>
            <th>Kor-Tps</th>
            <th>Alamat</th>
            <th>RT/RW</th>
            <th>Kelurahan / kecamatan</th>
            <th class="text-center">Tps</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Verif</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach ($anggota as $index => $data)
            <tr>
                 <td>{{ $index + 1 }}</td>
                <td>
                    {{ $data->nama }} <br>
                    <small>{{ $data->phone }}</small>
                </td>
                <td>'{{ $data->nik }}</td>
                <td>{{ $data->agents->nama }}</td>
                <td>{{ $data->agents->korlurs->nama }}</td>
                <td>{{ $data->alamat }}</td>
                <td>{{ $data->rt }} / {{ $data->rw }}</td>
                <td>
                    {{ $data->tps->kelurahans->nama_kelurahan }} <br>
                    <small> <b>{{ $data->tps->kelurahans->kecamatans->nama }} -
                            {{ $data->tps->kelurahans->kabkotas->nama }}</b></small>
                </td>
                <td>
                    {{ $data->tps->tps }} {{ $data->tps->kelurahans->nama_kelurahan }}
                </td>
                <td>{{ $data->keterangan }}</td>
                <td>
                    @if ($data->status == '1')
                        <span class="badge bg-label-success me-1">Aktif</span>
                    @elseif($data->status == '2')
                        <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    @if ($data->verified == '1')
                        <span class="badge bg-label-success me-1">Berhasil</span>
                    @elseif($data->verified == '2')
                        <span class="badge bg-label-danger me-1">Gagal</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
