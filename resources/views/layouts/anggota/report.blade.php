@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Anggota Report</h5>
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Nama atau NIK" name="nik"
                            value="{{ $request['nik'] }}" id="nik">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <input type="text" class="form-control" placeholder="agent" value="{{ $request['agent'] }}"
                            name="agent" id="agent">
                    </div>

                    <div class="col-sm-2 mt-2">
                        <input type="text" class="form-control" placeholder="kelurahan"
                            value="{{ $request['kelurahan'] }}" name="kelurahan" id="kelurahan">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <select class="form-select" name="verifikasi" id="verifikasi" value="{{ $request['verifikasi'] }}">
                            <option selected disabled value="">Verifikasi</option>
                            <option value="1">Sukses</option>
                            <option value="2">Gagal</option>
                        </select>
                    </div>
                    <div class="col-sm-2 mt-2">
                        <select class="form-select" name="tim" id="tim" value="{{ $request['tim'] }}">
                            <option selected disabled value="">Tim</option>
                            @foreach ($partai as $data)
                                <option value="{{$data->id}}">{{$data->nama}}</option>
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
        <div class="table-responsive text-nowrap">
            <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
                <a href="{{ route('report/anggota/excel') }}" class="btn btn-success me-2">Export</a>
            </div>
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>KTP</th>
                        <th>Agen</th>
                        <!--<th>Alamat</th>-->
                        <!--<th>RT/RW</th>-->
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
                            <!--<td>{{ $data->alamat }}</td>-->
                            <!--<td>{{ $data->rt }} / {{ $data->rw }}</td>-->
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
                            <td class>
                                @if ($user_role->role == '1' || $user_role->role == '2' || $user_role->role == '5')
                                
                                    <div class="dropdown text-center">
                                         <a href="{{ route('koordinator/anggota/report/edit', $data->id) }}">
                                            <button type="button" class="btn rounded-pill btn-warning"
                                                fdprocessedid="c80zr4">Edit</button>
                                        </a>
                                    @if ($data->verified_id == null && $data->verified == 2)
                                        <a href="{{ route('report/anggota/verif_lolos', $data->id) }}">
                                            <button type="button" class="btn rounded-pill btn-success">
                                                Lolos
                                            </button>
                                        </a>
                                        <a href="{{ route('report/anggota/verif_gagal', $data->id) }}">
                                            <button type="button" class="btn rounded-pill btn-danger"
                                                fdprocessedid="oqs0ur">Gagal</button>
                                        </a>
                                    </div>
                                    @else

                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $anggota->links() }}
        </div>
    </div>
   
@endsection
