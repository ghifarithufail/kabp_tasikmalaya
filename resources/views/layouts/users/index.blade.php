@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Users Table</h5>
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Nama atau username" name="name"
                            value="{{ $request['name'] }}" id="name">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <select class="form-select" name="partai" id="partai" value="{{ $request['partai'] }}">
                            <option selected disabled>Pilih Tim</option>
                            @foreach ($partais as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2" style="height: 40px"
                            id="search_btn">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mt-2" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-end">
            <a href="{{ route('user/create') }}" type="button" class="btn btn-primary ">Tambah</a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tim</th>
                        <th>Username</th>
                        <th>Kelurahan</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($user)
                        @foreach ($user as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->partais->nama }}</td>
                                <td>{{ $data->username }}</td>
                                <td>{{ $data->listKelurahan->nama_kelurahan }}</td>
                                <td><span
                                        class="badge @if ($data->status == 1) bg-label-success @else bg-label-danger @endif  me-1">{{ \App\Models\User::STATUS[$data->status] }}</span>
                                </td>
                                <td>
                                    @foreach($data->roles as $role)
                                        {{ $role->name }}

                                    @endforeach
                                    {{-- @if ($data->role == '1')
                                        Admin
                                    @elseif($data->role == '2')
                                        Korcam
                                    @elseif($data->role == '3')
                                        Korhan
                                    @elseif($data->role == '4')
                                        Kortps
                                    @elseif($data->role == '5')
                                        Owner
                                    @else
                                        -
                                    @endif --}}
                                </td>
                                <td class="d-flex">
                                    <a href="{{ route('user/edit', $data->id) }}" type="button"
                                        class="btn rounded-pill btn-warning">Edit</a>
                                    <form action="{{ route('user/destroy', $data->id) }}" method="post"
                                        enctype="multipart/form-data" class="ms-2" id="deleteUser-{{ $data->id }}">
                                        @csrf
                                        <button class="btn rounded-pill btn-danger" id="confirm-text" type="button"
                                            onclick="deleteItem(this)" value="{{ $data->id }}" >
                                            Delete</button>
                                    </form>
                                    {{-- <a href="{{ route('user/destroy', $data->id) }}" type="button" class="btn rounded-pill btn-danger">Delete</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-center mb-3">
                {{ $user->links() }}
            </div>
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

        // $(document).ready(function() {
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
        // });


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
