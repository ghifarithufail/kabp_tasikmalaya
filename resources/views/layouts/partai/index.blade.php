@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Tim</h5>
    </div>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end" style="zoom: 0.8">
            <a href="{{ route('data/partai/create') }}" type="button" class="btn btn-primary ">Tambah</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($partais as $partai)
                        <tr>
                            <td>
                                @if($partai->foto != null && Storage::exists('uploads/' . $partai->foto))
                                <img src="{{ asset('uploads/' . $partai->foto) }}" width="80"
                                        style="border-radius: 20%;">
                                @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="80" style="border-radius: 20%;">
                                @endif
                            </td>
                            <td>{{ $partai->nama }}</td>
                            <td>
                                <div class="dropdown text-center">
                                    <a href="{{ route('data/partai/edit', $partai->id) }}">
                                        <button type="button" class="btn rounded-pill btn-warning"
                                            fdprocessedid="c80zr4">Edit</button>
                                    </a>
                                    <a href="{{ route('data/partai/destroy', $partai->id) }}">
                                    <button type="button" class="btn rounded-pill btn-danger"
                                        fdprocessedid="oqs0ur">Delete</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
