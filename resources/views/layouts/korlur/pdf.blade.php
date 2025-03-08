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
    <h2 class="card-header" style="text-align: center">Koordinator Tpa {{ $korlur->nama }}</h2>
</div>
<div class="card mt-4">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover" style="zoom: 0.75">
            <thead>
                <tr>
                    <th>Agent</th>
                    <th>Tim</th>
                    <th>phone</th>
                    <th>KTP</th>
                    <th>Tps</th>
                    <th>Keterangan</th>
                    <th>Konstituen</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($agent as $data)
                    <tr>
                        <td>
                            {{ $data->nama }} 
                        </td>
                        <td>{{ $data->partais->nama }}</td>
                        <td>{{ $data->phone }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>
                            @foreach ($data->tps_pivot as $row)
                                {{ $row->tps }} {{ $row->kelurahans->nama_kelurahan }} <br>
                            @endforeach
                        </td>
                        <td>{{ $data->keterangan }}</td>
                        <td>{{ $data->anggotas_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
