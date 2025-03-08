<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #000;
        /* Add a border style here */
        padding: 8px;
        text-align: left;
    }
</style>
<div class="card text-center">
    <h2 class="card-header" style="text-align: center">Agent Tps {{ $agent->nama }}</h2>
</div>

<table>
    <thead>
        <tr>
            <b>RAIHAN TARGET KONSTITUEN PER TPS JEJARING KARYAWAN</b>
        </tr>
        <tr>
            <th>koord.Kecamatan : {{ $agent->korlurs->korcams->nama }}</th>
        </tr>
        <tr>
            <th>koord.Kelurahan : {{ $agent->korlurs->nama }}</th>
        </tr>
        <tr>
            <th>koord.TPS : {{ $agent->nama }}</th>
        </tr>
        <tr>
            <th>jmlh.Konstituante Lolos : {{ $lolos }}</th>
        </tr>
        {{-- <tr>
            @php
                $item = $anggota->first();
            @endphp

            @if ($item)
                <th>TPS : {{ $item->tps->tps }} - {{ $item->tps->kelurahans->nama_kelurahan }} -
                    {{ $item->tps->kelurahans->kecamatans->nama }}</th>
            @endif
        </tr> --}}
    </thead>
</table>
<div class="table-responsive text-nowrap">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Konstituen</th>
                <th>phone</th>
                <th>KTP</th>
                <th>Tps</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach ($anggota as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->phone }}</td>
                    <td>'{{ $data->nik }}</td>
                    <td>{{ $data->tps->tps }} {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                    <td>{{ $data->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<table>
    <thead>
        <tr>
            <b>RAIHAN TARGET KONSTITUEN PER TPS JEJARING KARYAWAN</b>
        </tr>
        <tr>
            <th>koord.Kecamatan : {{ $agent->korlurs->korcams->nama }}</th>
        </tr>
        <tr>
            <th>koord.Kelurahan : {{ $agent->korlurs->nama }}</th>
        </tr>
        <tr>
            <th>koord.TPS : {{ $agent->nama }}</th>
        </tr>
        <tr>
            <th>jmlh.Konstituante Gagal : {{ $gagal }}</th>
        </tr>
        {{-- <tr>
            @php
                $item = $anggota->first();
            @endphp

            @if ($item)
                <th>TPS : {{ $item->tps->tps }} - {{ $item->tps->kelurahans->nama_kelurahan }} -
                    {{ $item->tps->kelurahans->kecamatans->nama }}</th>
            @endif
        </tr> --}}
    </thead>
</table>
<div class="table-responsive text-nowrap">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Konstituen</th>
                <th>phone</th>
                <th>KTP</th>
                <th>Tps</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach ($anggota_gagal as $data)
                <tr>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->phone }}</td>
                    <td>'{{ $data->nik }}</td>
                    <td>{{ $data->tps->tps }} {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                    <td>{{ $data->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
