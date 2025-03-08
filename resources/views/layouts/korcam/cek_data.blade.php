@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Cek Koordinator Kecamatan </h5>
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
                        <th>Nama korlur</th>
                        <th>NIK</th>
                        <th>Phone</th>
                        <th>Kelurahan</th>
                        <th>Keterangan</th>
                        <th>Konstituen</th>
                        <th>Berhasil</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($korlur as $data)
                        <tr>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->kelurahan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>{{ $data->jumlah_anggota }}</td>
                            <td>{{ $data->berhasil }}</td>
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

{{-- @extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Log Kecamatan </h5>
    </div>
    <div class="card mt-4">
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-4 mt-2">
                        <input type="date" class="form-control" placeholder="NIK" name="date_start" value="{{ $request['date_start'] }}" id="date_start">
                    </div>
                    <div class="col-sm-4 mt-2">
                        <input type="date" class="form-control" placeholder="NIK" name="date_end" value="{{ $request['date_end'] }}" id="date_end">
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
                        <th>Nama</th>
                        <th>action</th>
                        <th>log</th>
                        <th>date</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($log as $data)
                        <tr>
                            <td>{{ $data->users->name }}</td>
                            <td>
                                @if ($data->action == '1')
                                    create
                                @elseif($data->action == '2')
                                    update
                                @elseif($data->action == '3')
                                    delete
                                @endif
                            </td>
                            <td>{{ $data->log }}</td>
                            <td>{{ $data->created_at->format('F d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection --}}

