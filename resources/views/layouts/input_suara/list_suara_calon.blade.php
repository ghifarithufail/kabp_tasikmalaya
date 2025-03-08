@extends('main')
@section('content')
    <div class="main-content">
        <div class="card text-center">
            <h5 class="card-header">List Suara Calon</h5>
            <div class="card-header" style="zoom: 0.8">
                <form>
                    <div class="form-group row">
                        <div class="col-sm-3 mt-2">
                            <input type="text" class="form-control" placeholder="Nama Calon" name="calon_walkot"
                                value="{{ $request['calon_walkot'] }}" id="calon_walkot">
                        </div>
                        <div class="col-sm-2 mt-2">
                            <input type="text" class="form-control" placeholder="tps" name="tps"
                                value="{{ $request['tps'] }}" id="tps">
                        </div>
                        <div class="col-sm-3 mt-2">
                            <input type="text" class="form-control" placeholder="Kelurahan" name="kelurahan"
                                value="{{ $request['kelurahan'] }}" id="kelurahan">
                        </div>
                        <div class="col-sm-3 mt-2">
                            <input type="text" class="form-control" placeholder="kecamatan" name="kecamatan"
                                value="{{ $request['kecamatan'] }}" id="kecamatan">
                        </div>
                        <div class="col-sm-3 mt-2">
                            <select class="form-select" name="kategori" id="kategori" value="{{ $request['kategori'] }}">
                                <option selected disabled>Pilih Tim</option>
                                <option value="walkot">Walkot</option>
                                <option value="gubernur">Gubernur</option>
                                <option value="bupati">Bupati</option>
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
        <div class="row mt-2">
            {{-- @foreach ($data as $key => $count) --}}
                <!--<div class="col-xl-4 col-lg-6 col-md-6 mt-2">-->
                <!--    <div class="card">-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex justify-content-between mb-2">-->
                <!--                <h6 class="fw-normal">Total TPS</h6>-->
                <!--            </div>-->
                <!--            <div class="d-flex justify-content-between align-items-end">-->
                <!--                <div class="role-heading">-->
                <!--                    <h4 class="mb-1">{{ number_format($totalTPS) }}</h4>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-xl-4 col-lg-6 col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="fw-normal">Persentase TPS Sudah Input Walkot Tasikmalaya</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="role-heading">
                                    <h4 class="mb-1">{{ $persentase }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @endforeach --}}
        </div>

        <div class="card mt-4">
            {{-- @can('create koordinator/anggota')
                <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
                    <a href="{{ route('koordinator/anggota/create') }}" type="button" class="btn btn-primary ">Tambah</a>
                </div>
            @endcan --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" style="zoom: 0.75">
                    <thead>
                        <tr>
                            <th>CALON</th>
                            <th>TPS</th>
                            <th>KELURAHAN</th>
                            <th>KECAMATAN</th>
                            <th>C1</th>
                            <th>SUARA</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $item)
                            <tr>
                                <td>{{$item->calons->name}}</td>
                                <td>{{$item->tps->tps}}</td>
                                <td>{{$item->tps->kelurahans->nama_kelurahan}}</td>
                                <td>{{$item->tps->kelurahans->kecamatans->nama}}</td>
                                 <td>
                                @if($item->bukti_pleno != null && file_exists(public_path('uploads/' . $item->bukti_pleno)))
                                <a href="{{ asset('uploads/' . $item->bukti_pleno) }}" target="_blank">
                                    <img src="{{ asset('uploads/' . $item->bukti_pleno) }}" width="80"
                                            style="border-radius: 20%;">
                                </a>
                                @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="80" style="border-radius: 20%;">
                                @endif
                            </td>
                                <td>{{$item->total_suara}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{ $data->links() }}
            </div>
        </div>

@endsection
