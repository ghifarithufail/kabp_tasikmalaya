@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Perolehan Suara Walikota Tasikmalaya</h5>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            <a href="{{ route('list_suara_calon') }}" type="button" class="btn btn-primary ">List Suara Calon</a>
        </div>

        <div class="card mt-4">
            <div class="container text-center mt-4">
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>

    <div class="card mt-2 p-4">
        <div class="row mb-12 g-4 mt-4">
            @foreach ($calon_walkot as $data)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-img-top text-center">
                            @if($data->gambar != null && file_exists(public_path('uploads/' . $data->gambar)))
                                <img src="{{ asset('uploads/' . $data->gambar) }}" width="80"
                                        style="border-radius: 20%;">
                                @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="80" style="border-radius: 20%;">
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
    
    <div class="card mt-2">
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>

    {!! $dataTable->scripts() !!}

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script>

    <script>
        $.ajax({
            url: "{{ route('fetch-geojeson') }}",
            type: 'GET',
            success: function(data) {
                console.log(data)
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });


        let ROOT_PATH = window.location.origin;
        var mapKotaTasik = echarts.init(document.getElementById('map'));

        fetch(ROOT_PATH + '/data/kotatasikmalaya.geojson')
            .then(response => response.json())
            .then(geoJson => {

                let dynamicData = geoJson.features.map(item => ({
                    id: item.id,
                    name: item.properties.name,
                    value: item.properties.paslon4,
                    totalDpt: item.properties.totalDpt,
                    paslon1: item.properties.paslon1,
                    paslon2: item.properties.paslon2,
                    paslon3: item.properties.paslon3,
                    paslon5: item.properties.paslon5
                }));

                const maxValueKelurahan = dynamicData.reduce((max, obj) => obj.value > max.value ? obj : max,
                    dynamicData[0]);

                echarts.registerMap('Tasikmalaya', geoJson);

                var option = {
                    title: {
                        text: 'Peta Kota Tasikmalaya',
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
                            let namaKelurahan = params.data.name;
                            let totalDpt = params.data.totalDpt;
                            let suaraPaslon1 = params.data.paslon1 || 0;
                            let suaraPaslon2 = params.data.paslon2 || 0;
                            let suaraPaslon3 = params.data.paslon3 || 0;
                            let suaraPaslon4 = params.data.value || 0;
                            let suaraPaslon5 = params.data.paslon5 || 0;
                            return `<div class="container" style="width:300px">
                                        <div class="row text-center"><b class="text-center">${namaKelurahan}</b></div>
                                        <div class="row">
                                            <div class="col-4 offset-4">Suara</div>
                                            <div class="col-4">Total Dpt</div>
                                        </div>
                                        <div class="row d-flex">
                                            <div class="col-4 flex-wrap text-wrap"><small>Paslon 1</small>: </div>
                                            <div class="col-4 border-end">${suaraPaslon1}</div>
                                        </div>
                                        <div class="row d-flex">
                                            <div class="col-4 flex-wrap text-wrap"><small>Paslon 2</small>: </div>
                                            <div class="col-4 border-end">${suaraPaslon2}</div>
                                        </div>
                                        <div class="row d-flex">
                                            <div class="col-4 flex-wrap text-wrap"><small>Paslon 3</small>: </div>
                                            <div class="col-4 border-end">${suaraPaslon3}</div>
                                            <div class="col-4">${totalDpt}</div>
                                        </div>
                                        <div class="row d-flex">
                                            <div class="col-4 flex-wrap text-wrap"><b>Paslon 4</b>: </div>
                                            <div class="col-4 border-end">${suaraPaslon4}</div>
                                        </div>
                                        <div class="row d-flex">
                                            <div class="col-4 flex-wrap text-wrap"><small>Paslon 5</small>: </div>
                                            <div class="col-4 border-end">${suaraPaslon5}</div>
                                        </div>
                                    </div>`
                        }
                    },
                    visualMap: {
                        min: 0,
                        max: maxValueKelurahan.value,
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
                        map: 'Tasikmalaya',
                        label: {
                            show: true,
                            fontSize: 7
                        },
                        zoom: 1.5, // Memperbesar peta
                        roam: true, // Izinkan zoom dan geser manual
                        data: dynamicData
                    }]
                };

                mapKotaTasik.setOption(option);
            });
    </script>
@endsection
