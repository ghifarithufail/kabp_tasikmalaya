<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
</style>

<div class="card mt-4">
    <div>
        <div>
            <td>
                <b>RAIHAN TARGET KONSTITUEN PER TPS JEJARING KARYAWAN</b>
            </td>
        </div>
        
        <div style="margin-top: 10px">
            <th>koord.Kecamatan : {{ $agent->korlurs->korcams->nama }}</th>
        </div>

        <div>
            <th>koord.Kelurahan : {{  $agent->korlurs->nama }}</th>
        </div>
        <div>
            <th>Agent TPS : {{  $agent->nama }}</th>
        </div>
        <div>
            <th>jmlh.Konstituante Lolos : {{ $lolos }}</th>
        </div>

        <div class="mb-3">
            @php
                $item = $anggota->first();
            @endphp

            @if ($item)
                <th>TPS : {{ $item->tps->tps }} - {{ $item->tps->kelurahans->nama_kelurahan }} -
                    {{ $item->tps->kelurahans->kecamatans->nama }}</th>
            @endif
        </div>
    </div>

    <div class="table-responsive text-nowrap" style="margin-top: 20px">
        <table class="table table-hover" style="zoom: 0.75">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Konstituen</th>
                    <th>phone</th>
                    <th>KTP</th>
                    <th>Tps</th>
                    <th>Keterangan</th>
                    <th>Verifikasi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($anggota as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->nama }}</td>
                        {{-- <td>{{ $data->partais->nama }}</td> --}}
                        <td>{{ $data->phone }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>{{ $data->tps->tps }} {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                        <td>{{ $data->keterangan }}</td>
                        <td>
                            @if ($data->verified == 1)
                                Lolos
                            @else
                                Gagal
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page break after 'gagal' variable -->
    <div style="page-break-before: always;"></div>
    <div>
        <div>
            <td>
                <b>RAIHAN TARGET KONSTITUEN PER TPS JEJARING KARYAWAN</b>
            </td>
        </div>
        
        <div style="margin-top: 10px">
            <th>koord.Kecamatan : {{ $agent->korlurs->korcams->nama }}</th>
        </div>

        <div>
            <th>koord.Kelurahan : {{  $agent->korlurs->nama }}</th>
        </div>
        <div>
            <th>Agent TPS : {{  $agent->nama }}</th>
        </div>
        <div>
            <th>jmlh.Konstituante Gagal : {{ $gagal }}</th>
        </div>

        <div class="mb-3">
            @php
                $item = $anggota->first();
            @endphp

            @if ($item)
                <th>TPS : {{ $item->tps->tps }} - {{ $item->tps->kelurahans->nama_kelurahan }} -
                    {{ $item->tps->kelurahans->kecamatans->nama }}</th>
            @endif
        </div>
    </div>

    <div class="table-responsive text-nowrap" style="margin-top: 20px">
        <table class="table table-hover" style="zoom: 0.75">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Konstituen</th>
                    <th>phone</th>
                    <th>KTP</th>
                    <th>Tps</th>
                    <th>Keterangan</th>
                    <th>Verifikasi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($anggota_gagal as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->nama }}</td>
                        {{-- <td>{{ $data->partais->nama }}</td> --}}
                        <td>{{ $data->phone }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>{{ $data->tps->tps }} {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                        <td>{{ $data->keterangan }}</td>
                        <td>
                            @if ($data->verified == 1)
                                Lolos
                            @else
                                Gagal
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
