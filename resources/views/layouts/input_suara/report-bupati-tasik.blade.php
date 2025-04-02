@extends('main')
@section('content')
    <!-- Menyisipkan CSS untuk ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.css">
    <div class="card text-center">
        <h5 class="card-header">Perolehan Suara Bupati Tasikmalaya</h5>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            {{-- <a href="{{ route('korcam/create') }}" type="button" class="btn btn-primary ">Tambah</a> --}}
        </div>

        <div class="card mt-4">
            <div class="container text-center mt-4">
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>

    {{-- <div class="card mt-2 p-4">
        <div class="row mb-12 g-4 mt-4">
            @foreach ($calon_kabupaten as $data)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-img-top">
                            @if ($data->gambar != null && Storage::exists('uploads/' . $data->gambar))
                                <img src="{{ asset('uploads/' . $data->gambar) }}" width="80"
                                    style="border-radius: 20%;">
                            @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="80"
                                    style="border-radius: 20%;">
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $data->name }}</h5>
                            <p class="card-text"> Total Suara : {{ $data->total_suara }}</p>
                            <p class="card-text">Persentase : {{ $data->persentase }} %</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}

    <div class="card mt-2 p-4 justify-content-center align-items-center">
        <div class="row me-auto">
            <h4><strong>Quick Count</strong></h4>
            <p>Total Perolehan Suara saat ini</p>
        </div>

        <div class="card-body">
            <div class="row ">
                <div id="chart"></div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.js"></script>

    <script>
        var apiUrl = '/report-bupati-tasik';
        var options;

        $.ajax({
            url: "{{ route('fetch-geojson-kabupaten-tasik') }}",
            type: 'GET',
            success: function(data) {
                console.log(data)
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });


        let ROOT_PATH = window.location.origin;
        var mapKabupatenTasik = echarts.init(document.getElementById('map'));

        fetch(ROOT_PATH + '/data/kabupatentasikmalaya.geojson')
            .then(response => response.json())
            .then(geoJson => {
                console.log(geoJson)
                echarts.registerMap('Kabupaten Tasikmalaya', geoJson);

                let option = {
                    title: {
                        text: 'Peta Kabupaten Tasikmalaya',
                        subtext: 'Data Perbandingan Hasil Survey dan QuickCount',
                        sublink: 'https://www.relawanprimaberkah.com',
                        left: 'right'
                    },
                    tooltip: {
                        trigger: 'item',
                        showDelay: 0,
                        transitionDuration: 0.2,
                        enterable: true,
                        formatter: function(params) {
                            return `{b0}: {c0}<br />{b1}: {c1}`

                        }
                    },
                    visualMap: {
                        min: 0,
                        max: 100,
                        left: 'left',
                        top: 'bottom',
                        inRange: {
                            color: [
                                '#a50026', // Merah
                                '#d73027',
                                '#f46d43',
                                '#fdae61',
                                '#fee08b',
                                '#ffffbf', // Kuning
                                '#d9ef8b',
                                '#a6d96a',
                                '#66bd63',
                                '#1a9850',
                                '#006837' // Hijau
                            ]
                        },
                        text: ['Terbanyak', 'Terendah'],
                        calculable: true
                    },
                    toolbox: {
                        show: true,
                        //orient: 'vertical',
                        left: 'left',
                        top: 'top',
                        feature: {
                            dataView: {
                                readOnly: false
                            },
                            restore: {},
                            saveAsImage: {}
                        }
                    },
                    // legend: {
                    //     textStyle: {
                    //         fontSize: 1  // Kecilkan font legend
                    //     }
                    // },
                    series: [{
                        type: 'map',
                        map: 'Kabupaten Tasikmalaya',
                        label: {
                            show: true,
                            fontSize: 7
                        },
                        center: [108.1568, -7.3894],
                        zoom: 100, // Memperbesar peta
                        roam: true, // Izinkan zoom dan geser manual
                        data: [{
                            name: 'BARUMEKAR',
                            value: 40
                        }, ]
                    }]
                };

                mapKabupatenTasik.setOption(option);
            });


        // Function to fetch data and update the chart
        function fetchDataAndUpdateChart() {
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Extract data from the response
                    var data = response.data;
                    var newSeries = [];
                    var newLabels = [];

                    // Populate series and labels arrays
                    data.forEach(function(item) {
                        let totalSuara = item.total_suara ? item.total_suara : 0;
                        newSeries.push(totalSuara);
                        newLabels.push(item.name);
                    });

                    // Check if the data has changed
                    if (JSON.stringify(newSeries) !== JSON.stringify(options.series) || JSON.stringify(
                            newLabels) !== JSON.stringify(options.labels)) {
                        // Update the chart options with new data
                        options.series = newSeries;
                        options.labels = newLabels;

                        // Update the chart with new data
                        chart.updateOptions(options);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error fetching data:', error);
                    alert('Failed to load data. Please try again later.');
                }
            });
        }

        $(document).ready(function() {

            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    // Extract data from the response
                    var data = response.data;
                    var series = [];
                    var labels = [];

                    // Populate series and labels arrays
                    data.forEach(function(item) {
                        let totalSuara = item.total_suara ? item.total_suara : 0;
                        series.push(totalSuara);
                        labels.push(item.name);
                    });

                    // Define the chart options
                    options = {
                        chart: {
                            type: 'pie', // Menentukan jenis chart
                            width: 820, // Lebar chart
                            dropShadow: {
                                enabled: true,
                            }
                        },
                        series: series, // Data series untuk pie chart
                        labels: labels, // Label untuk setiap bagian pie
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };
                    var chart = new ApexCharts(document.querySelector("#chart"), options)

                    // Render the chart
                    chart.render();
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error fetching data:', error);
                    alert('Failed to load data. Please try again later.');
                }
            });

            // Fetch data and update the chart every 5 seconds (5000 milliseconds)
            var intervalId = window.setInterval(fetchDataAndUpdateChart, 5000);

            // Optional: Clear the interval if needed (e.g., on page unload)
            $(window).on('beforeunload', function() {
                clearInterval(intervalId);
            });
        });
    </script>
@endsection
