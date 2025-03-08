@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report Kecamatan</h5>
    </div>

    <div class="card text-center mt-3">
        <div class="card-header" style="zoom: 0.8">
            <form id="filter_form">
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Kecamatan" name="kecamatan" id="kecamatan">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <select class="form-select" name="dapil" id="dapil">
                            <option selected value="">pilih dapil</option>
                            <option value="1">Dapil 1</option>
                            <option value="2">Dapil 2</option>
                            <option value="3">Dapil 3</option>
                            <option value="4">Dapil 4</option>
                        </select>
                    </div>
                    <div class="col-sm-1 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary rounded text-white mr-2" style="height: 40px" id="search_btn">Search</button>
                        <button type="button" class="btn btn-secondary rounded ms-3" style="height: 40px" id="reset_btn">Reset</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="table-responsive text-nowrap" id="report">
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
                <tbody class="table-border-bottom-0" id="load_report">
                    <tr>
                        <td colspan="8"><i class='bx bx-loader bx-spin bx-flip-vertical' style="font-size: 3rem;"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan data semua kecamatan atau berdasarkan filter
        function fetchRealTimeUpdates(kecamatan = '', dapil = '') {
            $.ajax({
                url: '/report/data_kecamatan', // Ganti dengan route yang sesuai
                method: 'GET',
                data: {
                    kecamatan: kecamatan,
                    dapil: dapil // Kirimkan filter dapil
                },
                dataType: 'json',
                success: function(data) {
                    // Update tampilan dengan data yang diterima
                    $('#report').html(data.data);
                },
                error: function(error) {
                    console.error('Error fetching real-time updates:', error);
                },
            });
        }

        $(document).ready(function() {
            fetchRealTimeUpdates();
        });

        $('#filter_form').on('submit', function(e) {
            e.preventDefault();

            var kecamatan = $('#kecamatan').val();
            var dapil = $('#dapil').val();

            // Kirim data ke fungsi fetchRealTimeUpdates
            fetchRealTimeUpdates(kecamatan, dapil);
        });

        $('#reset_btn').on('click', function() {
            // Kosongkan input dan select
            $('#kecamatan').val('');
            $('#dapil').val('');

            // Ambil data tanpa filter
            fetchRealTimeUpdates();
        });
    </script>
@endsection
