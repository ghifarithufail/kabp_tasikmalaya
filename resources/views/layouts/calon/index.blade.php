@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Calon Table</h5>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mt-2" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end">
            <a href="{{ route('data/calon/create') }}" type="button" class="btn btn-primary ">Tambah</a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Daerah Pemilihan</th>
                        <th>Status</th>
                        <th>Gambar Calon</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($calon as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            {{-- <td>
                                @foreach ($partai as $item)
                                    @if(in_array($item->id, explode(',', trim($data->partai))))
                                        {{ $item->nama }} &nbsp;  
                                    @endif
                                @endforeach
                            </td> --}}
                            <td>{{ ucwords($data->kategori) }}</td>
                            <td>{{ ucwords($data->daerah_pemilihan) }}</td>
                            
                            <td>
                                @if($data->status == 0)
                                <span class="badge bg-label-danger me-1">{{ \App\Models\Calon::STATUS[0] }}</span>
                                @else
                                <span class="badge bg-label-success me-1">{{ \App\Models\Calon::STATUS[1] }}</span>
                                @endif
                            </td>
                            <td>
                                @if($data->gambar != null && file_exists(public_path('uploads/' . $data->gambar)))
                                <img src="{{ asset('uploads/' . $data->gambar) }}" width="80"
                                        style="border-radius: 20%;">
                                @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="80" style="border-radius: 20%;">
                                @endif
                            </td>
                            </td>
                            <td class="d-flex mt-5 pb-5">
                                <a href="{{ route('data/calon/edit', $data->id) }}" type="button"
                                    class="btn rounded-pill btn-warning">Edit</a>
                                <form action="{{ route('data/calon/destroy', $data->id) }}" method="post"
                                    enctype="multipart/form-data" class="ms-2" id="deleteUser-{{ $data->id }}">
                                    @csrf
                                    <button class="btn rounded-pill btn-danger" id="confirm-text" type="button"
                                        onclick="deleteItem(this)" value="{{ $data->id }}">
                                        Delete</button>
                                </form>
                                {{-- <a href="{{ route('user/destroy', $data->id) }}" type="button" class="btn rounded-pill btn-danger">Delete</a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteItem(data) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus saja!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteUser-' + data.value).submit();
                }
            });
        }

        $(document).ready(function() {
            // var success = "{{ session('success') }}";

            // if (success) {
            //     Swal.fire({
            //         title: 'Success!',
            //         // text: 'Data User Berhasil Disimpan',
            //         text: success,
            //         icon: 'success',
            //         type: 'success',
            //         customClass: {
            //             confirmButton: 'btn btn-primary'
            //         },
            //         buttonsStyling: false
            //     });
            // }
        });
    </script>
@endsection
