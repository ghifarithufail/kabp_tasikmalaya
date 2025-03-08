@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report TPS</h5>
    </div>
    <div class="card text-center mt-3">
        <div class="card-header" style="zoom: 0.8">
            <form id="filter_form">
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="kelurahan" value="{{ $request['kelurahan'] }}"
                            name="kelurahan" id="kelurahan">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Kecamatan"
                            value="{{ $request['kecamatan'] }}" name="kecamatan" id="kecamatan">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="tps" value="{{ $request['filter_tps'] }}"
                            name="filter_tps" id="filter_tps">
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
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            <button id="exportBtn" class="btn btn-success">Export Tps</button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover text-center" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Kelurahan</th>
                        <th>Tps</th>
                        <th>Kecamatan</th>
                        <th>Kab/Kota</th>
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
                    @foreach ($tps as $data)
                        <tr>
                            <td>{{ $data->nama_kelurahan }}</td>
                            <td>
                                <a href="{{ route('report/tps/detail', $data->id) }}">
                                    {{ $data->tps }}
                                </a>
                            </td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->kota }}</td>
                            <td>{{ $data->totdpt }}</td>
                            <td>{{ $data->target }}</td>
                            @foreach ($tim as $partai)
                                <td>{{ $data->{'anggota_partai_' . $partai->id} }}</td>
                            @endforeach
                            <td>{{ $data->total_data_anggota }}</td>
                            <td>
                                <span class="badge text-bg-warning"
                                    style="width: 70px; height: 30px; font-size: 16px;">{{ $data->total_verified_anggota }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $tps->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
    $('#exportBtn').click(function () {
        // Menampilkan notifikasi bahwa proses download dimulai
        Swal.fire({
            title: 'Mengunduh...',
            text: 'Silakan tunggu sebentar.',
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Menjalankan AJAX untuk mengunduh file Excel
        $.ajax({
            url: "{{ route('report/tps/export') }}", // Route untuk mengunduh Excel
            method: "GET",
            xhrFields: {
                responseType: 'blob' // Mengatur agar response diterima sebagai blob
            },
            success: function (data) {
                const url = window.URL.createObjectURL(new Blob([data]));
                const a = document.createElement('a');
                a.href = url;
                a.download = 'tps.xlsx'; // Nama file yang akan diunduh
                document.body.appendChild(a);
                a.click();
                a.remove();

                // Menampilkan notifikasi bahwa unduhan telah selesai
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Download telah selesai.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Terjadi kesalahan!',
                    text: 'Kesalahan saat mengunduh file. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

    </script>
@endsection
