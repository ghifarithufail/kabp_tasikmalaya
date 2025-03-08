@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Perolehan Suara Bupati Garut</h5>
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

    <div class="card mt-2 p-4">
        <div class="row mb-12 g-4 mt-4">
            @foreach ($calon_garut as $data)
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script>

    <script>
        $.ajax({
            url: "{{ route('fetch-geojson-kabupaten-garut') }}",
            type: 'GET',
            success: function(data) {
                console.log(data)
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });


        let ROOT_PATH = window.location.origin;
        var mapKabupatenGarut = echarts.init(document.getElementById('map'));

        fetch(ROOT_PATH + '/data/kabupatengarut.geojson')
            .then(response => response.json())
            .then(geoJson => {
                console.log(geoJson)
                echarts.registerMap('Kabupaten Garut', geoJson);

                let option = {
                    title: {
                        text: 'Peta Kabupaten Garut',
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
                        map: 'Kabupaten Garut',
                        label: {
                            show: true,
                            fontSize: 7
                        },
                        zoom: 60, // Memperbesar peta
                        roam: true, // Izinkan zoom dan geser manual
                        center: [107.7304, -7.3346],
                        data: [{
                            name: 'CIKANDANG',
                            value: 40
                        }, ]
                    }]
                };

                mapKabupatenGarut.setOption(option);
            });

    </script>
@endsection
