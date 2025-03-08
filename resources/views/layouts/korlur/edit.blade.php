@extends('main')
@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Koordinator Tps Edit</h5>
        <div class="card-body">
            <form action="{{ route('koordinator/korlur/update', $korlur->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation @if ($errors->any()) was-validated @endif">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="nama" value="{{ $korlur->nama }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" required />
                            @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Phone</label>
                        <input type="number" name="phone" value="{{ $korlur->phone }}" id="phone" class="form-control"
                            placeholder="Masukan No telpon" required />
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nik">Nomor KTP</label>
                        <input type="number" name="nik" value="{{ $korlur->nik }}" id="nik" class="form-control"
                            placeholder="Masukan NIK" required />
                            @error('nik')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="name">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="{{ $korlur->tgl_lahir }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" required />
                            @error('tgl_lahir')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="exampleFormControlSelect1" class="form-label">Kota Lahir</label>
                        <select class="form-select" name="kabkota_id" id="kabkota_id" required>
                            <option value="{{ $korlur->kabkota_id }}" selected>{{ old('kabkota_id', $korlur->kabkotas->nama) }} </option>
                        </select>
                        @error('kabkota_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Alamat</label>
                        <input type="text" name="alamat" value="{{ $korlur->alamat }}" id="multicol-username"
                            class="form-control" placeholder="alamat" required />
                            @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RT</label>
                        <input type="number" name="rt" value="{{ $korlur->rt }}" id="multicol-username"
                            class="form-control" placeholder="Rt" required />
                            @error('rt')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RW</label>
                        <input type="number" value="{{ $korlur->rw }}" name="rw" id="multicol-username"
                            class="form-control" placeholder="RW" required />
                            @error('rw')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Kelurahan</label>
                        <select class="form-select" name="kelurahan_id" id="kelurahan_id" required>
                            <option value="{{ $korlur->kelurahan_id }}" selected>{{ old('kelurahan_id', $korlur->kelurahans->nama_kelurahan) }} </option>

                        </select>
                        @error('kelurahan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Korcam</label>
                        <select class="form-select" name="korcam_id" id="korcam_id"  required>
                            <option value="{{$korlur->korcam_id}}">== {{$korlur->korcams->nama}} - {{$korlur->kelurahans->kecamatans->nama}} ==</option>
                            @foreach ($korcam as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                        @error('korcam_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Tps</label>
                        <select class="form-select" name="tps_id" id="tps_id" required>
                            <option value="{{ $korlur->tps_id }}" selected>{{ old('tps_id', $korlur->tps->tps) }}  - {{ old('tps_id', $korlur->kelurahans->nama_kelurahan) }} </option> $

                        </select>
                        @error('tps_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Tim</label>
                        <select class="form-select" name="partai_id" id="partai_id"
                            aria-label="Default select example" required>
                            <option value="{{$korlur->partai_id}}">== {{$korlur->partais->nama}} ==</option>
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
                            aria-label="Default select example" required>
                            <option value="{{ $korlur->status }}" selected> @if ($korlur->status == '1')
                                == Aktif ==
                            @else
                                == Non Aktif ==
                            @endif</option>
                            <option value="1">Aktif</option>
                            <option value="2">Non Aktif</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Keterangan</label>
                        <input type="text" value="{{ $korlur->keterangan }}" name="keterangan" id="multicol-username"
                            class="form-control" placeholder="Keterangan" required />
                            @error('keterangan')
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
            $('#partai_id').select2();
            $('#status').select2();
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

        $(document).ready(function() {
            $('#kelurahan_id').select2({
                placeholder: 'Pilih Kelurahan',
                allowClear: true,
                ajax: {
                    url: "{{ route('getKelurahans') }}",
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
                                    text: item.nama_kelurahan + ' - ' + item.kecamatans.nama
                                }
                            })
                        };
                    },
                },
            });
        });

        $(document).ready(function() {
            $('#tps_id').select2({
                placeholder: 'Pilih Tps',
                allowClear: true,
                ajax: {
                    url: "{{ route('getTps') }}",
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
                                    text: item.tps + ' - ' + item.kelurahans.nama_kelurahan + ' - ' + item.kelurahans.kecamatans.nama 
                                }
                            })
                        };
                    },
                },
            });
        });

        $(document).ready(function() {
            $('#korcam_id').select2({
                placeholder: 'Pilih Korcam',
                allowClear: true,
                ajax: {
                    url: "{{ route('getKorcams') }}",
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
                                    text: item.nama + ' - ' + item.kecamatans.nama
                                }
                            })
                        };
                    },
                },
            });
        });
    </script>
@endsection
