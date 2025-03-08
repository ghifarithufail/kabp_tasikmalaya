 <table class="table table-hover text-center" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Kelurahan</th>
                        <th>Tps</th>
                        <th>Kecamatan</th>
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
                        $currentDapil = '';
                        $subtotalDPT = 0;
                        $subtotalTarget = 0;
                        $totalDPT = 0;
                        $totalTarget = 0;

                        $totalPerPartai = [];
                        $totalKonstituante = 0;
                        $totalVerifikasi = 0;

                        // Initialize totals per party
                        foreach ($tim as $partai) {
                            $totalPerPartai[$partai->id] = 0;
                        }

                        // Initialize total overall for each party
                        $grandTotalPerPartai = [];
                        foreach ($tim as $partai) {
                            $grandTotalPerPartai[$partai->id] = 0;
                        }
                        $grandTotalKonstituante = 0;
                        $grandTotalVerifikasi = 0;
                    @endphp

                    @foreach ($tps as $data)
                        @if ($currentDapil != $data->dapil)
                            {{-- Close the previous Dapil section, if needed --}}
                            @if ($currentDapil != '')
                                {{-- Display the Subtotal for the previous Dapil --}}
                                <tr style="background-color: #e9ecef;">
                                    <td colspan="3" style="background-color: #e9ecef;">Subtotal Kecamatan di Dapil {{ $currentDapil }}:</td>
                                    <td style="background-color: #e9ecef;">{{ number_format($subtotalDPT) }}</td>
                                    <td style="background-color: #e9ecef;">{{ number_format($subtotalTarget) }}</td>
                                    @foreach ($tim as $partai)
                                        <td style="background-color: #e9ecef;">{{ number_format($totalPerPartai[$partai->id]) }}</td>
                                    @endforeach
                                    <td style="background-color: #e9ecef;">{{ number_format($totalKonstituante) }}</td>
                                    <td style="background-color: #e9ecef;">{{ number_format($totalVerifikasi) }}</td>
                                </tr>
                            @endif

                            {{-- Reset subtotals and change Dapil --}}
                            @php
                                $currentDapil = $data->dapil;
                                $subtotalDPT = 0;
                                $subtotalTarget = 0;
                                $totalKonstituante = 0;
                                $totalVerifikasi = 0;

                                // Reset totals per party
                                foreach ($tim as $partai) {
                                    $totalPerPartai[$partai->id] = 0;
                                }
                            @endphp

                            {{-- Display the Dapil header --}}
                            <tr style="background-color: #d4edda;">
                                <td colspan="10" style="background-color: #d4edda; align-item:center">Dapil {{ $currentDapil }}</td>
                            </tr>
                        @endif

                        {{-- Display each Kecamatan data --}}
                        <tr>
                            <td>{{ $data->nama_kelurahan }}</td>
                            <td>
                                    {{ $data->tps }}
                            </td>
                            <td>{{ $data->kecamatan }}</td>
                            <td>{{ number_format($data->totdpt) }}</td>
                            <td>{{ number_format($data->target) }}</td>
                            @foreach ($tim as $partai)
                                <td>{{ number_format($data->{'anggota_partai_' . $partai->id}) }}</td>
                                @php
                                    // Sum for each party
                                    $totalPerPartai[$partai->id] += $data->{'anggota_partai_' . $partai->id};
                                    $grandTotalPerPartai[$partai->id] += $data->{'anggota_partai_' . $partai->id};
                                @endphp
                            @endforeach
                            <td>{{ number_format($data->total_data_anggota) }}</td>
                            <td>{{ number_format($data->total_verified_anggota) }}</td>

                            @php
                                // Sum the konstituante and verifikasi sukses
                                $totalKonstituante += $data->total_data_anggota;
                                $totalVerifikasi += $data->total_verified_anggota;

                                $grandTotalKonstituante += $data->total_data_anggota;
                                $grandTotalVerifikasi += $data->total_verified_anggota;

                                // Sum the overall totals
                                $subtotalDPT += $data->totdpt;
                                $subtotalTarget += $data->target;

                                $totalDPT += $data->totdpt;
                                $totalTarget += $data->target;
                            @endphp
                        </tr>
                    @endforeach

                    {{-- Display the final subtotal for the last Dapil --}}
                    <tr style="background-color: #e9ecef;">
                        <td colspan="3" style="background-color: #e9ecef;">Subtotal Kecamatan di Dapil {{ $currentDapil }}:</td>
                        <td style="background-color: #e9ecef;">{{ number_format($subtotalDPT) }}</td>
                        <td style="background-color: #e9ecef;">{{ number_format($subtotalTarget) }}</td>
                        @foreach ($tim as $partai)
                            <td style="background-color: #e9ecef;">{{ number_format($totalPerPartai[$partai->id]) }}</td>
                        @endforeach
                        <td style="background-color: #e9ecef;">{{ number_format($totalKonstituante) }}</td>
                        <td style="background-color: #e9ecef;">{{ number_format($totalVerifikasi) }}</td>
                    </tr>

                    {{-- Display total overall --}}
                    <tr style="background-color: #d4edda;">
                        <td colspan="3" style="background-color: #d4edda;">Total Keseluruhan:</td>
                        <td style="background-color: #d4edda;">{{ number_format($totalDPT) }}</td>
                        <td style="background-color: #d4edda;">{{ number_format($totalTarget) }}</td>
                        @foreach ($tim as $partai)
                            <td style="background-color: #d4edda;">{{ number_format($grandTotalPerPartai[$partai->id]) }}</td>
                        @endforeach
                        <td style="background-color: #d4edda;">{{ number_format($grandTotalKonstituante) }}</td>
                        <td style="background-color: #d4edda;">{{ number_format($grandTotalVerifikasi) }}</td>
                    </tr>
                </tbody>
            </table>
