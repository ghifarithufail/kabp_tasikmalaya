@foreach($korcams as $key => $datas)
    @foreach($datas['korlurs'] as $item)
<th height="50px" style="font-size: 20; font-weight: 800; font-family: Arial Narrow; font-style: italic">FORM TANDA
    TERIMA KORDES LUR</th> <br>
<table>
    <tbody>
        <tr>
            <td width="30px">HARI</td>
            <td width="130px">-</td>
            <td width="100px">TANGGAL</td>
            <td width="30px">-</td>
            <td></td>
            <td></td>
            <td></td>
            <td>KAB/KOTA</td>
            <td>Kota Tasikmalaya</td>
        </tr>
        <tr>
            <td>KECAMATAN</td>
            <td></td>
            <td>{{$datas['korcam']->kecamatans->nama}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>DAPIL</td>
            <td>{{$item['korlur']->kelurahans->dapil}}</td>
        </tr>
        <tr>
            <td>KELURAHAN</td>
            <td></td>
            <td>{{$item['korlur']->kelurahans->nama_kelurahan}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>TEAM</td>
            <td>{{$datas['korcam']->partais->nama}}</td>
        </tr>
        <tr>
            <td>KORLUR</td>
            <td></td>
            <td>{{$item['korlur']->nama}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>KORCAM</td>
            <td>{{$datas['korcam']->nama}}</td>
        </tr>
    </tbody>
</table>
<table>
    <thead >
        <tr>
            <th bgcolor="#d8d8d8" style="border: 4px solid black; font-weight: bold;" align="center" valign="center" height="20px" rowspan="2">NO</th>
            <th bgcolor="#d8d8d8" align="center" valign="center" rowspan="2" style="border: 4px solid black; font-weight: bold;">NAMA AGEN</th>
            <th bgcolor="#d8d8d8" align="center" valign="center"rowspan="2" style="border: 4px solid black; font-weight: bold;">NOMOR HP</th>
            <th bgcolor="#d8d8d8" align="center" valign="center"rowspan="2" style="border: 4px solid black; font-weight: bold;">TPS</th>
            <th bgcolor="#d8d8d8" align="center" valign="center" height="30px" style="border: 4px solid black; font-weight: bold;" colspan="4">JUMLAH</th>
            <th bgcolor="#d8d8d8" align="center" valign="center"rowspan="2" style="border: 4px solid black; font-weight: bold;" width="120px">TTD PENERIMA</th>
        </tr>
        <tr>
            <th bgcolor="#d8d8d8" align="center" valign="center" height="50px" width="80px" style="border: 4px solid black; font-weight: bold;">KONSTITUEN</th>
            <th bgcolor="#d8d8d8" align="center" valign="center" height="50px" width="110px" style="border: 4px solid black; font-weight: bold;">Rp.</th>
            <th bgcolor="#d8d8d8" align="center" valign="center" height="50px" width="80px" style="border: 4px solid black; font-weight: bold; word-wrap: break-word">KOMISI 5000 PER KTP</th>
            <th bgcolor="#d8d8d8" align="center" valign="center" height="50px" width="90px" style="border: 4px solid black; font-weight: bold;">DITERIMA</th>
        </tr>
    </thead>
    <tbody class="costum-border">
        @php
         $jumlahKonstituen = 0;
         $jumlahDiterima = 0;
        @endphp
        @foreach($item['agents'] as $agent)
        <tr>
            <td valign="center" height="40px" style="border: 4px solid black;">1</td>
            <td valign="center" height="40px" style="border: 4px solid black;">{{$agent['agent']->nama}}</td>
            <td valign="center" align="right" height="40px" style="border: 4px solid black;">{{$agent['agent']->phone}}</td>
            <td valign="center" align="center" height="40px" style="border: 4px solid black;">
                {{ isset($agent['anggotas'][0]) ? $agent['anggotas'][0]->tps_id : '' }}
            </td>
            <td valign="center" align="right" height="40px" style="border: 4px solid black;">{{$konstituen = count($agent['anggotas'])}}</td>
            <td valign="center" align="right" height="40px" style="border: 4px solid black;">100000</td>
            <td valign="center" align="right" height="40px" style="border: 4px solid black;">{{ (5000 * count($agent['anggotas']) )}}</td>
            <td valign="center" align="right" height="40px" style="border: 4px solid black;">{{ $diterima = ((100000 * count($agent['anggotas'])) + (5000 * count($agent['anggotas'])) )}}</td>
            <td valign="center" height="40px" style="border: 4px solid black;">-</td>
            @php
                $jumlahKonstituen += $konstituen;
                $jumlahDiterima += $diterima;
            @endphp
        </tr>
        @endforeach
        <tr>
            <td height="50px" valign="center" align="left" style="font-weight: 800; border: 4px solid black;" colspan="4">JUMLAH</td>
            <td height="50px" valign="center" align="right" style="font-weight: 800; border: 4px solid black;">{{$jumlahKonstituen}}</td>
            <td height="50px" style="border: 4px solid black;"></td>
            <td height="50px" style="border: 4px solid black;"></td>
            <td height="50px" valign="center" align="right" style="font-weight: 800; border: 4px solid black;">{{$jumlahDiterima}}</td>
            <td height="50px" style="border: 4px solid black;"></td>
        </tr>
    </tbody>
</table>

    @endforeach
@endforeach