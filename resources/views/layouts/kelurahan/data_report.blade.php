<table class="table table-hover text-center" style="zoom: 0.75">
    <thead>
        <tr>
            <th>Kelurahan</th>
            <th>Kecamatam</th>
            <th>Total DPT</th>
            <th>Target</th>
            <th>Konstituante</th>
            <th>Verifikasi Sukses</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @php
            $current_dapil = '';
            $total_dpt_per_dapil = 0;
            $total_target_per_dapil = 0;
            $total_anggota_per_dapil = 0;
            $total_verified_per_dapil = 0;

            $grand_total_dpt = 0;
            $grand_total_target = 0;
            $grand_total_anggota = 0;
            $grand_total_verified = 0;
        @endphp

        @foreach ($kelurahan as $data)
            @if ($current_dapil !== $data->kecamatans->dapil)
                @if ($current_dapil !== '')
                    <tr style="background-color: ">
                        <td style="text-align: left;"><strong>Subtotal Kelurahan di Dapil
                                {{ $current_dapil }}:</strong></td>
                        <td><strong>{{ number_format($kecamatan) }}</strong></td>        
                        <td><strong>{{ number_format($total_dpt_per_dapil) }}</strong></td>
                        <td><strong>{{ number_format($total_target_per_dapil) }}</strong></td>
                        <td><strong>{{ number_format($total_anggota_per_dapil) }}</strong></td>
                        <td><strong>{{ number_format($total_verified_per_dapil) }}</strong></td>
                    </tr>
                @endif

                {{-- Reset dapil counters --}}
                @php
                    $current_dapil = $data->dapil;
                    $total_dpt_per_dapil = 0;
                    $total_target_per_dapil = 0;
                    $total_anggota_per_dapil = 0;
                    $total_verified_per_dapil = 0;
                @endphp

                {{-- Display new DAPIL heading --}}
                <tr>
                    <td colspan="5" style="background-color: #dff0d8; text-align: left;">
                        <strong>DAPIL {{ $current_dapil }}</strong>
                    </td>
                </tr>
            @endif

            {{-- Display Kecamatan data --}}
            <tr>
                <td style="text-align: left;">
                    <a href="{{ route('report/kecamatan/detail', $data->id) }}">
                        {{ $data->nama }}
                    </a>
                </td>
                <td>{{ number_format($data->total_dpt) }}</td>
                <td>{{ number_format($data->total_target) }}</td>
                <td>{{ number_format($data->total_data_anggota) }}</td>
                <td>{{ number_format($data->total_verified_anggota) }}</td>
            </tr>

            {{-- Add to dapil totals --}}
            @php
                $total_dpt_per_dapil += $data->total_dpt;
                $total_target_per_dapil += $data->total_target;
                $total_anggota_per_dapil += $data->total_data_anggota;
                $total_verified_per_dapil += $data->total_verified_anggota;

                $grand_total_dpt += $data->total_dpt;
                $grand_total_target += $data->total_target;
                $grand_total_anggota += $data->total_data_anggota;
                $grand_total_verified += $data->total_verified_anggota;
            @endphp
        @endforeach

        {{-- Last dapil subtotal --}}
        <tr style="background-color: ">
            {{-- <tr style="background-color: #dff0d8"> --}}
            <td style="text-align: left;"><strong>Subtotal Kecamatan di Dapil
                    {{ number_format($current_dapil) }}:</strong></td>
            <td><strong>{{ number_format($total_dpt_per_dapil) }}</strong></td>
            <td><strong>{{ number_format($total_target_per_dapil) }}</strong></td>
            <td><strong>{{ number_format($total_anggota_per_dapil) }}</strong></td>
            <td><strong>{{ number_format($total_verified_per_dapil) }}</strong></td>
        </tr>

        {{-- Grand total row --}}
        <tr style="background-color: #c6e0f5">
            <td style="text-align: left;"><strong>Total Keseluruhan:</strong></td>
            <td><strong>{{ number_format($grand_total_dpt) }}</strong></td>
            <td><strong>{{ number_format($grand_total_target) }}</strong></td>
            <td><strong>{{ number_format($grand_total_anggota) }}</strong></td>
            <td><strong>{{ number_format($grand_total_verified) }}</strong></td>
        </tr>
    </tbody>
</table>
