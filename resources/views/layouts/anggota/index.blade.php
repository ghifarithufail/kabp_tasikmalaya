@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Anggota</h5>
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Nama atau NIK" name="nik"
                            value="{{ $request['nik'] }}" id="nik">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="agent" value="{{ $request['agent'] }}" name="agent" id="agent">
                    </div>

                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="kelurahan" value="{{ $request['kelurahan'] }}" name="kelurahan" id="kelurahan">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <select class="form-select" name="verifikasi" id="verifikasi" value="{{ $request['verifikasi'] }}">
                            <option selected disabled value="">Verifiaksi</option>
                            <option value="1">Sukses</option>
                            <option value="2">Gagal</option>

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
        @can('create koordinator/anggota')
            <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
                <a href="{{ route('koordinator/anggota/create') }}" type="button" class="btn btn-primary ">Tambah</a>
            </div>
        @endcan
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>No</th> <!-- Kolom No -->
                        <th>Nama</th>
                        <th>KTP</th>
                        <th>Agen</th>
                        <th>Jenis Kelamin</th>
                        <th>Usia</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Kelurahan / kecamatan</th>
                        <th class="text-center">Tps</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Verif</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($anggota as $index => $data)
                        <tr>
                            <td>{{ $index + 1 + ($anggota->currentPage() - 1) * $anggota->perPage() }}</td> <!-- Menambahkan nomor urut -->
                            <td>
                                {{ $data->nama }} <br>
                                <small>{{ $data->phone }}</small>
                            </td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->agents->nama }}</td>
                            <td>{{ $data->jenis_kelamin }}</td>
                            <td>{{ $data->usia }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->rt }} / {{ $data->rw }}</td>
                            <td>
                                {{ $data->tps->kelurahans->nama_kelurahan }} <br>
                                <small><b>{{ $data->tps->kelurahans->kecamatans->nama }} -
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
                            <td>
                                <div class="dropdown text-center">
                                    @can('update koordinator/anggota')
                                        <a href="{{ route('koordinator/anggota/edit', $data->id) }}">
                                            <button type="button" class="btn rounded-pill btn-warning"
                                                fdprocessedid="c80zr4">Edit</button>
                                        </a>
                                    @endcan
        
                                    @can('delete koordinator/anggota')
                                        <a href="{{ route('koordinator/anggota/destroy', $data->id) }}">
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
        </div>
        <div>
            {{ $anggota->links() }}
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
