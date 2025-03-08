<table class="table table-hover text-center" style="zoom: 0.75">
    <thead>
        <tr>
            <th>Kecamatan</th>
            <th>Total DPT</th>
            <th>Target</th>
            @foreach ($tim as $data)
                <th>{{ $data->nama }}</th>
            @endforeach
            <th>Konstituante</th>
            <th>Verifikasi Sukses</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @php
            // Initialize DAPIL variables
            $current_dapil = '';
            $total_dpt_per_dapil = 0;
            $total_target_per_dapil = 0;
            $total_anggota_per_dapil = 0;
            $total_verified_per_dapil = 0;

            // Initialize grand totals
            $grand_total_dpt = 0;
            $grand_total_target = 0;
            $grand_total_anggota = 0;
            $grand_total_verified = 0;

            // Initialize party totals array for DAPIL subtotal and grand total
            $dapil_party_totals = [];
            $grand_party_totals = array_fill_keys($tim->pluck('id')->toArray(), 0);
        @endphp

        @foreach ($kecamatan as $data)
            @if ($current_dapil !== $data->dapil)
                {{-- Display previous DAPIL subtotal if it exists --}}
                @if ($current_dapil !== '')
                    <tr style="background-color: #f5f5f5;">
                        <td><strong>Subtotal Kecamatan di Dapil {{ $current_dapil }}:</strong></td>
                        <td><strong>{{ $total_dpt_per_dapil }}</strong></td>
                        <td><strong>{{ $total_target_per_dapil }}</strong></td>
                        @foreach ($tim as $partai)
                            <td><strong>{{ $dapil_party_totals[$partai->id] ?? 0 }}</strong></td>
                        @endforeach
                        <td><strong>{{ $total_anggota_per_dapil }}</strong></td>
                        <td><strong>{{ $total_verified_per_dapil }}</strong></td>
                    </tr>
                @endif

                {{-- Reset counters for new DAPIL --}}
                @php
                    $current_dapil = $data->dapil;
                    $total_dpt_per_dapil = 0;
                    $total_target_per_dapil = 0;
                    $total_anggota_per_dapil = 0;
                    $total_verified_per_dapil = 0;
                    $dapil_party_totals = array_fill_keys($tim->pluck('id')->toArray(), 0); // Reset party totals for the new DAPIL
                @endphp
                {{-- Display DAPIL heading --}}
                <tr>
                    <td colspan="{{ 5 + $tim->count() }}" style="background-color: #dff0d8; text-align: left;">
                        <strong>DAPIL {{ $current_dapil }} {{ $data->kabkota }}</strong>
                    </td>
                </tr>
            @endif

            {{-- Display Kecamatan data --}}
            <tr>
                <td style="text-align: left;">
                    {{-- <a href="{{ route('report/kecamatan/detail', $data->id) }}"> --}}
                        {{ $data->nama }}
                    {{-- </a> --}}
                </td>
                <td>{{ $data->total_dpt }}</td>
                <td>{{ $data->total_target }}</td>

                {{-- Display and accumulate partai data --}}
                @foreach ($tim as $partai)
                    @php
                        $partai_count = $data->{'anggota_partai_' . $partai->id} ?? 0;
                        $dapil_party_totals[$partai->id] += $partai_count; // Add to DAPIL total
                        $grand_party_totals[$partai->id] += $partai_count; // Add to grand total
                    @endphp
                    <td>{{ $partai_count }}</td>
                @endforeach

                <td>{{ $data->total_data_anggota }}</td>
                <td>{{ $data->total_verified_anggota }}</td>
            </tr>

            {{-- Accumulate DAPIL and grand totals --}}
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

        {{-- Display last DAPIL subtotal if it has data --}}
        @if (
            $total_dpt_per_dapil > 0 ||
                $total_target_per_dapil > 0 ||
                $total_anggota_per_dapil > 0 ||
                $total_verified_per_dapil > 0)
            <tr style="background-color: #f5f5f5;">
                <td style="text-align: left;"><strong>Subtotal Kecamatan di Dapil {{ $current_dapil }}:</strong></td>
                <td><strong>{{ $total_dpt_per_dapil }}</strong></td>
                <td><strong>{{ $total_target_per_dapil }}</strong></td>
                @foreach ($tim as $partai)
                    <td><strong>{{ $dapil_party_totals[$partai->id] ?? 0 }}</strong></td>
                @endforeach
                <td><strong>{{ $total_anggota_per_dapil }}</strong></td>
                <td><strong>{{ $total_verified_per_dapil }}</strong></td>
            </tr>
        @endif

        {{-- Grand total row --}}
        <tr style="background-color: #c6e0f5">
            <td style="text-align: left;"><strong>Total Keseluruhan:</strong></td>
            <td><strong>{{ $grand_total_dpt }}</strong></td>
            <td><strong>{{ $grand_total_target }}</strong></td>
            @foreach ($tim as $partai)
                <td><strong>{{ $grand_party_totals[$partai->id] }}</strong></td>
            @endforeach
            <td><strong>{{ $grand_total_anggota }}</strong></td>
            <td><strong>{{ $grand_total_verified }}</strong></td>
        </tr>

    </tbody>
</table>
