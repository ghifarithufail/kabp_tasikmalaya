@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Koordinator Kecamatan</h5>
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Nama atau NIK" name="nik"
                            value="{{ $request['nik'] }}" id="nik">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Kecamatan" name="kecamatan" id="kecamatan">
                    </div>
                    <div class="col-sm-3 mt-2">
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
            <a href="{{ route('report/korlur/general/excel') }}" type="button" class="btn btn-success ">Excel</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tim</th>
                        <th>KTP</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>kecamatan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Korlur</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($korcam as $data)
                        <tr>
                            <td>
                                <a href="{{ route('report/korcam/detail', $data->id) }}">
                                    {{ $data->nama }} <br>
                                    <small>{{ $data->phone }}</small>
                                </a>
                            </td>
                            <td>{{ $data->partais->nama }}</td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->rt }} / {{ $data->rw }}</td>
                            <td>{{ $data->kecamatans->nama }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>
                                @if ($data->status == '1')
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                @elseif($data->status == '2')
                                    <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{ $data->korlurs_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            {{ $korcam->links() }}
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
