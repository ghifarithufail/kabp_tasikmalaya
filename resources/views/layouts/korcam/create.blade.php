@extends('main')
@section('content')
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
            integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Select2 JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>

    <div class="card mb-4">
        <h5 class="card-header">Koordinator Kecamatan Create</h5>
        <div class="card-body">
            <form action="{{ route('koordinator/korcam/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" />
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Phone</label>
                        <input type="number" name="phone" value="{{ old('phone') }}" id="phone" class="form-control"
                            placeholder="Masukan No telpon" />
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nik">NIK</label>
                        <input type="number" name="nik" value="{{ old('nik') }}" id="nik" class="form-control"
                            placeholder="Masukan NIK" />
                        @error('nik')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="name">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" />
                        @error('tgl_lahir')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="kabkota_id" class="form-label">Kota Lahir</label>
                        <select class="form-select" name="kabkota_id" id="kabkota_id">
                            {{-- <option selected>{{ old('kabkota_id', 'pilih kota lahir') }}</option>
                            @foreach ($kabkota as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach --}}
                        </select>
                        @error('kabkota_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" id="multicol-username"
                            class="form-control" placeholder="alamat" />
                        @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RT</label>
                        <input type="number" name="rt" value="{{ old('rt') }}" id="multicol-username"
                            class="form-control" placeholder="Rt" />
                        @error('rt')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RW</label>
                        <input type="number" value="{{ old('rw') }}" name="rw" id="multicol-username"
                            class="form-control" placeholder="RW" />
                        @error('rw')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kecamatan_id">Kecamatan</label>
                        <select class="form-select" name="kecamatan_id" id="kecamatan_id">
                            {{-- <option selected disabled>{{ old('kecamatan_id', 'pilih kecamatan') }}</option>
                            @foreach ($kecamatan as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach --}}
                        </select>
                        @error('kecamatan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Tim</label>
                        <select class="form-select" name="partai_id" id="partai_id"
                            aria-label="Default select example">
                            <option value="">Pilih Tim</option>
                            @foreach ($partai as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                        @error('partai_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Status</label>
                        <select class="form-select" name="status" id="status"
                            aria-label="Default select example" >
                            <option selected disabled>{{ old('status', 'Pilih Status') }}</option>
                            <option value="1">Aktif</option>
                            <option value="2">Non Aktif</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Keterangan</label>
                        <input type="text" value="{{ old('keterangan') }}" name="keterangan" id="multicol-username"
                            class="form-control" placeholder="Keterangan" />
                        @error('Keterangan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="pt-5 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        showLoading();
        $(document).ready(function() {
            hideLoading();
            $('#partai_id').select2()
            $('#status').select2()
        });

        $(document).ready(function() {
            $('#kecamatan_id').select2({
                placeholder: 'Pilih Kecamatan',
                allowClear: true,
                ajax: {
                    url: "{{ route('getKecamatan') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                }
                            })
                        };
                    },
                },
            });
        });

        $(document).ready(function() {
            $('#kabkota_id').select2({
                placeholder: 'Pilih Kota Lahir',
                allowClear: true,
                ajax: {
                    url: "{{ route('getKabkotas') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                }
                            })
                        };
                    },
                },
            });
        });
    </script>
@endsection
