<table class="table table-hover text-center" style="zoom: 0.75">
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
            <tr>
                <td colspan="{{ 6 + count($tim) }}" style="background-color: #dff0d8; text-align: left;"><strong>Dapil {{ $dapil }}</strong></td>
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
    
            <tr>
                <td style="text-align: left; background-color: #e9ecef;"><strong>Subtotal Kelurahan di Dapil {{ $dapil }}</strong></td>
                <td style="background-color: #e9ecef;">{{ number_format($subtotal_dpt) }}</td>
                <td style="background-color: #e9ecef;">{{ number_format($subtotal_target) }}</td>
    
                @foreach ($tim as $partai)
                    <td style="background-color: #e9ecef;">{{ number_format($subtotal_partai[$partai->id]) }}</td> {{-- Tampilkan subtotal per partai --}}
                @endforeach
    
                <td style="background-color: #e9ecef;">{{ number_format($subtotal_data_anggota) }}</td>
                <td style="background-color: #e9ecef;">{{ number_format($subtotal_verified) }}</td>
            </tr>
    
            @php
                $total_all_dpt += $subtotal_dpt;
                $total_all_target += $subtotal_target;
                $total_all_data_anggota += $subtotal_data_anggota;
                $total_all_verified += $subtotal_verified;
            @endphp
        @endforeach
    
        <tr>
            <td style="background-color: #dff0d8; text-align: left;"><strong>Total Keseluruhan</strong></td>
            <td style="background-color: #dff0d8;">{{ number_format($total_all_dpt) }}</td>
            <td style="background-color: #dff0d8;">{{ number_format($total_all_target) }}</td>
    
            @foreach ($tim as $partai)
                <td style="background-color: #dff0d8;">{{ $total_all_partai ? $total_all_partai[$partai->id] : 0 }}</td>
                {{-- <td>{{ number_format($total_all_partai[$partai->id]) }}</td> Tampilkan total keseluruhan per partai --}}
            @endforeach
    
            <td style="background-color: #dff0d8;">{{ number_format($total_all_data_anggota) }}</td>
            <td style="background-color: #dff0d8;">{{ number_format($total_all_verified) }}</td>
        </tr>
    </tbody>
</table>