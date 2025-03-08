@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Agent Tps</h5>
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="NIK AGENT" name="nik"
                            value="{{ $request['nik'] }}" id="nik">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="kelurahan" value="{{ $request['kelurahan'] }}" name="kelurahan" id="kelurahan">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="korlur" value="{{ $request['korlur'] }}" name="korlur" id="korlur">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <select class="form-select" name="partai" id="partai" value="{{ $request['partai'] }}">
                            <option selected disabled>Pilih Tim</option>
                            @foreach ($partais as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2" style="height: 40px"
                            id="search_btn">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            <a href="{{ route('report/agent/export-agent') }}" class="btn btn-success me-2">Export Agent</a>
            <a href="{{ route('report/agent/general/excel') }}" class="btn btn-primary">Export General</a>
        </div>
        
        <div class="table-responsive text-nowrap">
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
                            @if ($data->belum != null)
                            
                                <tr>
                                    <td>
                                        <a href="{{ route('report/agent/detail', [$data->id, $data->id_tps ?? 0] ) }}">
                                            {{ $data->nama_koordinator }} <br>
                                            <small>{{ $data->phone }}</small>
                                        </a>
                                    </td>                                
                                    <td>{{ $data->nik }}</td>
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
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            @if ($agent != null)
                {{ $agent->links() }}
            @endif
        </div>
    </div>
    <script>
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
