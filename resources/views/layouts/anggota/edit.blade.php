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
        <h5 class="card-header">Anggota Update</h5>
        <div class="card-body">
            <form action="{{ route('koordinator/anggota/update', $anggota->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation @if ($errors->any()) was-validated @endif">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="nama" value="{{ $anggota->nama }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" required />
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Telepon</label>
                        <input type="number" name="phone" value="{{ $anggota->phone }}" id="phone" class="form-control"
                            placeholder="Masukan No telpon" required />
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nik">NIK</label>
                        <input type="number" name="nik" value="{{ $anggota->nik }}" id="nik" class="form-control"
                            placeholder="Masukan NIK" required />
                        @error('nik')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="name">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="{{ $anggota->tgl_lahir }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" required />
                        @error('tgl_lahir')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="exampleFormControlSelect1" class="form-label">Kota Lahir</label>
                        <select class="form-select" name="kabkota_id" id="kabkota_id" required>
                            <option value="{{ $anggota->kabkota_id }}" selected>{{ old('kabkota_id', $anggota->kabkotas->nama) }} </option>
                        </select>
                        @error('kabkota_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Alamat</label>
                        <input type="text" name="alamat" value="{{ $anggota->alamat }}" id="multicol-username"
                            class="form-control" placeholder="alamat" required />
                        @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Usia</label>
                        <input type="text" name="usia" value="{{ $anggota->usia }}" id="multicol-username"
                            class="form-control" placeholder="alamat" required />
                        @error('usia')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin" id="jenis_kelamin"
                                aria-label="Default select example" required>
                                <option value="{{$anggota->jenis_kelamin}}">
                                    @if ($anggota->jenis_kelamin == "L")
                                        == Laki - Laki == 
                                    @else
                                        == Perempuan ==
                                    @endif
                                </option>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        @error('jenis_kelamin')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RT</label>
                        <input type="number" name="rt" value="{{ $anggota->rt }}" id="multicol-username"
                            class="form-control" placeholder="Rt" required />
                        @error('rt')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RW</label>
                        <input type="number" value="{{ $anggota->rw }}" name="rw" id="multicol-username"
                            class="form-control" placeholder="RW" required />
                        @error('rw')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- <div class="col-md-6">
                        <label class="form-label" for="agent_id">Agent Tps</label>
                        <select class="form-select" name="agent_id" id="agent_id" required>
                            <option value="{{ $anggota->agent_id }}" selected>{{ old('agent_id', $anggota->agents->nama) }} </option>
                            
                        </select>
                        @error('agent_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    <div class="col-md-6">
                        <label class="form-label" for="tps_id">Tps</label>
                        <select class="form-select" name="tps_id" id="tps_id" required>
                            <option value="{{ $anggota->tps_id }}" selected>{{ old('agent_id', $anggota->tps->tps) }} - {{$anggota->tps->kelurahans->nama_kelurahan}} </option>
                        </select>
                        @error('korlur_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Status</label>
                        <select class="form-select" name="status" id="exampleFormControlSelect1"
                            aria-label="Default select example" required>
                            <option value="{{ $anggota->status }}" selected>
                                @if ($anggota->status == '1')
                                    == Aktif ==
                                @else
                                    == Non Aktif ==
                                @endif
                            </option>
                            <option value="1">Aktif</option>
                            <option value="2">Non Aktif</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Keterangan</label>
                        <input type="text" value="{{ $anggota->keterangan }}" name="keterangan" id="multicol-username"
                            class="form-control" placeholder="Keterangan"/>
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
            $('#agent_id').select2({
                placeholder: 'Pilih Agent',
                allowClear: true,
                ajax: {
                    url: "{{ route('getAgentTps') }}",
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
                                    text: item.nama + ' - ' + item.kelurahans.nama_kelurahan + ' - ' + item.kelurahans.kecamatans.nama
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
                                    text: item.tps + ' - ' + item.kelurahans.nama_kelurahan +
                                        ' - ' + item.kelurahans.kecamatans.nama
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
    </script>
@endsection
