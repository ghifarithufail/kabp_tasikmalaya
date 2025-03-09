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
        <h5 class="card-header">Agent Tps Create</h5>
        <div class="card-body">
            <form action="{{ route('koordinator/agent/update', $agent->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation @if ($errors->any()) was-validated @endif">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="nama" value="{{ $agent->nama }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" required />
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Telepon</label>
                        <input type="number" name="phone" value="{{ $agent->phone }}" id="phone" class="form-control"
                            placeholder="Masukan No telpon" required />
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nik">Nomor KTP</label>
                        <input type="number" name="nik" value="{{ $agent->nik }}" id="nik" class="form-control"
                            placeholder="Masukan NIK" required />
                        @error('nik')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- <div class="col-md-6">
                        <label class="form-label" for="name">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="{{ $agent->tgl_lahir }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" required />
                        @error('tgl_lahir')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="exampleFormControlSelect1" class="form-label">Kota Lahir</label>
                        <select class="form-select" name="kabkota_id" id="kabkota_id" required>
                            @if($agent->kabkotas)
                                <option value="{{ $agent->kabkota_id }}" selected>{{ old('kabkota_id', $agent->kabkotas->nama) }} </option>
                            @else
                                <option value="{{ $agent->kabkota_id }}" selected>{{ old('kabkota_id', $agent->kelurahans->kabkotas->nama) }} </option>
                            @endif

                        </select>
                        @error('kabkota_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Alamat</label>
                        <input type="text" name="alamat" value="{{ $agent->alamat }}" id="multicol-username"
                            class="form-control" placeholder="alamat" required />
                        @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RT</label>
                        <input type="number" name="rt" value="{{ $agent->rt }}" id="multicol-username"
                            class="form-control" placeholder="Rt" required />
                        @error('rt')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">RW</label>
                        <input type="number" value="{{ $agent->rw }}" name="rw" id="multicol-username"
                            class="form-control" placeholder="RW" required />
                        @error('rw')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Tim</label>
                        <select class="form-select" name="partai_id" id="partai_id"
                            aria-label="Default select example" required>
                            <option value="{{ $agent->partai_id }}">== {{ $agent->partais->nama }} ==</option>
                            @foreach ($partai as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                        @error('partai_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Kelurahan</label>
                        <select class="form-select" name="kelurahan_id" id="kelurahan_id" required>
                            <option value="{{ $agent->kelurahan_id }}" selected>{{ old('kelurahan_id', $agent->kelurahans->nama_kelurahan) }} </option>

                        </select>
                        @error('kelurahan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Koordinator Tps</label>
                        <select class="form-select" name="korlur_id" id="korlur_id"
                            aria-label="Default select example" required>
                            <option value="{{ $agent->korlur_id }}" selected>{{ old('kelurahan_id', $agent->korlurs ? $agent->korlurs->nama : '-') }} </option>
                        </select>
                        @error('korlur_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">TPS</label>
                        <select class="form-select" name="tps_id[]" id="tps_id" multiple="multiple" required >
                            @foreach ($tps as $item)
                                <option value="{{ $item->id }}"
                                    {{ in_array($item->id, old('tps_id', $agent->tps_pivot->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $item->tps }} - {{ $kelurahans[$item->kelurahan_id]->nama_kelurahan }}
                                </option>
                            @endforeach
                        </select>
                        @error('korlur_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-username">Status</label>
                        <select class="form-select" name="status" id="status"
                            aria-label="Default select example" required>
                            <option value="{{ $agent->status }}" selected>
                                @if ($agent->status == '1')
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
                        <input type="text" value="{{ $agent->keterangan }}" name="keterangan" id="multicol-username"
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
            $('#korlur_id').select2({
                placeholder: 'Pilih Korlur',
                allowClear: true,
                ajax: {
                    url: "{{ route('getKorlurs') }}",
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
