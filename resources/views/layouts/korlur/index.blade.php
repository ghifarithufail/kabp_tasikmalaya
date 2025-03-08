@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Koordinator TPS</h5>

        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Nama atau NIK" name="nik"
                            value="{{ $request['nik'] }}" id="nik">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <input type="text" class="form-control" placeholder="korcam" name="korcam" id="korcam"
                            value="{{ $request['korcam'] }}">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <input type="text" class="form-control" placeholder="kelurahan" name="kelurahan" id="kelurahan"
                            value="{{ $request['kelurahan'] }}">
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
        @can('create koordinator/korlur')
            <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
                <a href="{{ route('koordinator/korlur/create') }}" type="button" class="btn btn-primary ">Tambah</a>
            </div>
        @endcan

        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>KTP</th>
                        <th>Tim</th>
                        <th>Korcam</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Kelurahan / kecamatan</th>
                        <th>Tps</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($korlur as $data)
                        <tr>
                            <td>
                                {{ $data->nama }} <br>
                                <small>{{ $data->phone }}</small>
                            </td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->partais->nama }}</td>
                            <td>{{ $data->korcams->nama }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->rt }} / {{ $data->rw }}</td>
                            <td>
                                {{ $data->kelurahans->nama_kelurahan }} <br>
                                <small> <b>{{ $data->kelurahans->kecamatans->nama }} -
                                        {{ $data->kelurahans->kabkota }}</b></small>
                            </td>
                            <td>{{ $data->tps->tps }} - {{ $data->tps->kelurahans->nama_kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>
                                @if ($data->status == '1')
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                @elseif($data->status == '2')
                                    <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown text-center">
                                    @can('update koordinator/korlur')
                                        <a href="{{ route('koordinator/korlur/edit', $data->id) }}">
                                            <button type="button" class="btn rounded-pill btn-warning"
                                                fdprocessedid="c80zr4">Edit</button>
                                        </a>
                                    @endcan
                                    @can('delete koordinator/korlur')
                                        <a href="{{ route('koordinator/korlur/destroy', $data->id) }}">
                                            <button type="button" class="btn rounded-pill btn-danger"
                                                fdprocessedid="oqs0ur">Delete</button>
                                        </a>
                                    @endcan

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $korlur->links() }}
            </div>
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
