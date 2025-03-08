@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report Kelurahan</h5>
    </div>
    <div class="card text-center mt-3">
        <div class="card-header" style="zoom: 1">
            <form id="filter_form">
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Kelurahan" value="{{ $request['lurah'] }}"
                            name="lurah" id="lurah">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <input type="text" class="form-control" placeholder="Kecamatan"
                            value="{{ $request['kecamatan'] }}" name="kecamatan" id="kecamatan">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <input type="text" class="form-control" placeholder="Kabupaten" value="{{ $request['kabupaten'] }}"
                            name="kabupaten" id="kabupaten">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <select class="form-select" name="dapil" id="dapil">
                            <option @if($request['dapil'] == null) selected  @endif disabled>pilih dapil</option>
                            <option @if($request['dapil'] == 1) selected  @endif  value="1">Dapil 1</option>
                            <option @if($request['dapil'] == 2) selected  @endif  value="2">Dapil 2</option>
                            <option @if($request['dapil'] == 3) selected  @endif  value="3">Dapil 3</option>
                            <option @if($request['dapil'] == 4) selected  @endif  value="4">Dapil 4</option>
                        </select>
                    </div>
                    <div class="col-sm-1 d-flex">
                        <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2" style="height: 40px"
                            id="search_btn">Search</button>
                        <button type="button" class="btn btn-warning rounded text-white mt-2 mr-2 ms-3" style="height: 40px"
                        id="clear_input">Clear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover text-center" style="zoom: 0.75">
                <div class="card-header d-flex justify-content-end" style="zoom: 0.75">
                    <a href="{{ route('report/kelurahan/excel/donwload') }}">
                        <button id="exportBtn" class="btn btn-success">Export Kelurahan</button>
                    </a>
                </div>
                <thead>
                    <tr>
                        <th>Kelurahan</th>
                        <th>Total DPT</th>
                        <th>Target</th>
                        @foreach ($tim as $data)
                            <th>{{ $data->nama }}</th> {{-- Nama partai --}}
                        @endforeach
                        <th>Konstituante</th>
                        <th>Verifikasi Sukses</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $total_all_dpt = 0;
                        $total_all_target = 0;
                        $total_all_data_anggota = 0;
                        $total_all_verified = 0;
                
                        // Array untuk menyimpan total keseluruhan per partai
                        $total_all_partai = [];
                    @endphp
                
                    @foreach ($kelurahansByDapil as $dapil => $kelurahanGroup)
                        <tr style="background-color: #d4edda;">
                            <td colspan="{{ 6 + count($tim) }}" style="text-align: left;"><strong>Dapil {{ $dapil }}</strong></td>
                        </tr>
                
                        @php
                            $subtotal_dpt = 0;
                            $subtotal_target = 0;
                            $subtotal_data_anggota = 0;
                            $subtotal_verified = 0;
                
                            // Array untuk menyimpan subtotal per partai di tiap dapil
                            $subtotal_partai = [];
                        @endphp
                
                        @foreach ($kelurahanGroup as $data)
                            <tr>
                                <td style="text-align: left;">{{ $data->nama_kelurahan }}</td>
                                <td>{{ number_format($data->total_dpt) }}</td>
                                <td>{{ number_format($data->total_target) }}</td>
                
                                @foreach ($tim as $partai)
                                    @php
                                        // Jumlah anggota partai untuk kelurahan ini
                                        $jumlah_anggota = $data->{'anggota_partai_' . $partai->id};
                
                                        // Tambahkan ke subtotal per partai
                                        if (!isset($subtotal_partai[$partai->id])) {
                                            $subtotal_partai[$partai->id] = 0;
                                        }
                                        $subtotal_partai[$partai->id] += $jumlah_anggota;
                
                                        // Tambahkan ke total keseluruhan per partai
                                        if (!isset($total_all_partai[$partai->id])) {
                                            $total_all_partai[$partai->id] = 0;
                                        }
                                        $total_all_partai[$partai->id] += $jumlah_anggota;
                                    @endphp
                                    <td>{{ number_format($jumlah_anggota) }}</td>
                                @endforeach
                
                                <td>{{ number_format($data->total_data_anggota) }}</td>
                                <td>{{ number_format($data->total_verified_anggota) }}</td>
                            </tr>
                
                            @php
                                $subtotal_dpt += $data->total_dpt;
                                $subtotal_target += $data->total_target;
                                $subtotal_data_anggota += $data->total_data_anggota;
                                $subtotal_verified += $data->total_verified_anggota;
                            @endphp
                        @endforeach
                
                        <tr style="background-color: #e9ecef;">
                            <td style="text-align: left;"><strong>Subtotal Kelurahan di Dapil {{ $dapil }}</strong></td>
                            <td>{{ number_format($subtotal_dpt) }}</td>
                            <td>{{ number_format($subtotal_target) }}</td>
                
                            @foreach ($tim as $partai)
                                <td>{{ number_format($subtotal_partai[$partai->id]) }}</td> {{-- Tampilkan subtotal per partai --}}
                            @endforeach
                
                            <td>{{ number_format($subtotal_data_anggota) }}</td>
                            <td>{{ number_format($subtotal_verified) }}</td>
                        </tr>
                
                        @php
                            $total_all_dpt += $subtotal_dpt;
                            $total_all_target += $subtotal_target;
                            $total_all_data_anggota += $subtotal_data_anggota;
                            $total_all_verified += $subtotal_verified;
                        @endphp
                    @endforeach
                
                    <tr style="background-color: #d4edda;">
                        <td style="text-align: left;"><strong>Total Keseluruhan</strong></td>
                        <td>{{ number_format($total_all_dpt) }}</td>
                        <td>{{ number_format($total_all_target) }}</td>
                
                        @foreach ($tim as $partai)
                            <td>{{ $total_all_partai ? $total_all_partai[$partai->id] : 0 }}</td>
                            {{-- <td>{{ number_format($total_all_partai[$partai->id]) }}</td> Tampilkan total keseluruhan per partai --}}
                        @endforeach
                
                        <td>{{ number_format($total_all_data_anggota) }}</td>
                        <td>{{ number_format($total_all_verified) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let clearInput = document.getElementById('clear_input');
        clearInput.addEventListener('click', function(){
            let kabupatenInput = document.getElementById('kabupaten');
            let lurahInput = document.getElementById('lurah');
            let kecamatanInput = document.getElementById('kecamatan');
            let dapilSelect = document.getElementById('dapil');

            kabupatenInput.value = '';
            lurahInput.value = '';
            kecamatanInput.value = '';
            dapilSelect.selectedIndex = -1; // Menghapus seleksi di dropdown
        });

    </script>
@endsection
