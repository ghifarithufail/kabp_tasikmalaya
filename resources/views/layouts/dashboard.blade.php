@extends('main')
@section('content')
    @if ($account->role == '1' || $account->role == '2' || $account->role == '5')
        <div class="main-content">
            <div class="card text-center">
                <h5 class="card-header">Dashboard</h5>
            </div>

            <div class="row mt-2">
                @foreach ($data as $key => $count)
                    <div class="col-xl-4 col-lg-6 col-md-6 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="fw-normal">Total {{ number_format($count) }} {{ ucfirst($key) }}</h6>
                                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                            class="avatar avatar-sm pull-up" aria-label="Vinnie Mostowy"
                                            data-bs-original-title="Vinnie Mostowy">
                                            <img class="rounded-circle" src="/img/avatars/1.png" alt="Avatar">
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-flex justify-content-between align-items-end">
                                    <div class="role-heading">
                                        <h4 class="mb-1">{{ ucfirst($key) }}</h4>
                                    </div>
                                    <a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card text-center mt-4">
                <h3 class="card-header">Chart Anggota</h3>
                <hr>
                <div>
                    <canvas id="myChart" style="width: 100%; height: 350px;"></canvas> <!-- Mengatur tinggi menjadi 400px -->
                </div>
            </div>
        </div>
    @endif

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            var _ydata = JSON.parse('{!! json_encode($months) !!}');
            var _xdata = JSON.parse('{!! json_encode($monthCounth) !!}');

            const ctx = document.getElementById('myChart').getContext('2d');

            // Definisikan warna yang berbeda untuk setiap bulan
            var backgroundColors = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
            ];

            var borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
            ];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: _ydata,
                    datasets: [{
                        label: 'Total Anggota',
                        data: _xdata,
                        backgroundColor: backgroundColors.slice(0, _ydata.length),
                        borderColor: borderColors.slice(0, _ydata.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        </script>
    @endsection
