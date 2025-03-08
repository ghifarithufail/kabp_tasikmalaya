@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Penanggung Jawab TPS {{ $tps[0]->nama_tps }} Kelurahan {{ $tps[0]->nama_kelurahan }}</h5>
    </div>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            {{-- <a href="{{ route('korcam/create') }}" type="button" class="btn btn-primary ">Tambah</a> --}}
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover " style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama / NIK</th>
                        <th>No. Telfon</th>
                        <th>Tgl Lahir</th>
                        <th>RT / RW</th>
                        <th>Alamat</th>
                        <th>Tim</th>
                        <th>Status</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($kortps as $data)
                        <tr>
                            <td>
                                <a href="{{ route('report/agent/detail', ['id'=>$data->agent_tps_id, 'tps'=>$data->tps_id]) }}">
                                    {{ $data->nama_agent }} <br>
                                    <small>{{ $data->nik }}</small>
                                </a>
                            </td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->tgl_lahir }} </td>
                            <td>{{ $data->rt }} / {{ $data->rw }} </td>
                            <td>{{ $data->alamat }} </td>
                            <td>{{ $data->nama_partai }} </td>
                            
                            <td>
                                @if($data->status)
                                    <span class="badge bg-label-success me-1">{{ \App\Models\User::STATUS[$data->status] }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
