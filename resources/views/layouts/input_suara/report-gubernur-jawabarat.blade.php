@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Perolehan Suara Gubernur Jawa Barat</h5>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            {{-- Tombol atau fitur tambahan dapat ditambahkan di sini --}}
        </div>

        <div class="card mt-4">
            <div class="row mb-12 g-4 mt-4" id="calon-gubernur-container">
                {{-- Data akan dimuat melalui JavaScript --}}
            </div>
        </div>
    </div>

    <script>
        function fetchCalonGubernur() {
            $.ajax({
                url: '{{ url('/report/gubernur-jawabarat') }}', // Ganti dengan rute Anda
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let content = '';
                    response.data.forEach(data => {
                        const gambar = data.gambar && data.gambar !== null ?
                            '{{ asset('uploads/') }}/' + data.gambar :
                            '{{ asset('img/img-placeholder.png') }}';

                        // Format angka dengan pemisah ribuan
                        const formattedTotalSuara = Number(data.total_suara).toLocaleString('id-ID');

                        content += `
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 150px;">
                                        <img src="${gambar}" class="img-fluid" width="80" style="border-radius: 20%;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">${data.name}</h5>
                                        <p class="card-text">Total Suara: ${formattedTotalSuara}</p>
                                        <p class="card-text">Persentase: ${data.persentase} %</p>
                                    </div>
                                </div>
                            </div>`;
                    });
                    $('#calon-gubernur-container').html(content);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        // Panggil fungsi fetch pertama kali saat halaman dimuat
        $(document).ready(function() {
            fetchCalonGubernur();

            // Set interval untuk memuat ulang data setiap 5 detik
            setInterval(fetchCalonGubernur, 3000);
        });
    </script>
@endsection
