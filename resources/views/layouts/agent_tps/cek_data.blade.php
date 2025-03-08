@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Cek Agent </h5>
    </div>
    <div class="card mt-4">
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-4 mt-2">
                        <input type="text" class="form-control" placeholder="NIK" name="nik" id="nik">
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2" style="height: 40px"
                            id="search_btn">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama Konstituen</th>
                        <th>NIK</th>
                        <th>Phone</th>
                        <th>Kelurahan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($agent as $data)
                        <tr>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->tps }} - {{ $data->nama_kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        }, false);

        // Disable Ctrl + C
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'c' || e.key === 'C')) {
                e.preventDefault();
            }
        });
    </script>
@endsection
