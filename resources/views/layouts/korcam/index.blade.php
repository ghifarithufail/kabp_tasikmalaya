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
        @can('create koordinator/korcam')
            <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
                <a href="{{ route('koordinator/korcam/create') }}" type="button" class="btn btn-primary ">Tambah</a>
            </div>
        @endcan

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
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($korcams as $korcam)
                        <tr>
                            <td>
                                {{ $korcam->nama }} <br>
                                <small>{{ $korcam->phone }}</small>
                            </td>
                            @if($korcam->partais != null)
                            <td>{{ $korcam->partais->nama }}</td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $korcam->nik }}</td>
                            <td>{{ $korcam->alamat }}</td>
                            <td>{{ $korcam->rt }} / {{ $korcam->rw }}</td>
                            <td>{{ $korcam->kecamatans->nama }}</td>
                            <td>{{ $korcam->keterangan }}</td>
                            <td>
                                @if ($korcam->status == '1')
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                @elseif($korcam->status == '2')
                                    <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown text-center">
                                    @can('update koordinator/korcam')
                                        <a href="{{ route('koordinator/korcam/edit', $korcam->id) }}">
                                            <button type="button" class="btn rounded-pill btn-warning"
                                                fdprocessedid="c80zr4">Edit</button>
                                        </a>
                                    @endcan

                                    @can('delete koordinator/korcam')
                                        <a href="{{ route('koordinator/korcam/destroy', $korcam->id) }}">
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
                {{ $korcams->links() }}
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
