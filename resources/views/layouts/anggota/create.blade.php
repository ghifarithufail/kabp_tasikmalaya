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
            <h5 class="card-header">Anggota Create</h5>
            <div class="card-body">
                <form action="{{ route('koordinator/anggota/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Nama</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" id="bs-validation-name"
                                class="form-control" placeholder="Masukan Nama" />
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- @php
                            dd(Auth()->user()->kelurahan_id)
                        @endphp --}}
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Telepon</label>
                            <input type="number" name="phone" value="{{ old('phone') }}" id="phone"
                                class="form-control" placeholder="Masukan No telpon" />
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nik">NIK</label>
                            <input type="number" name="nik" value="{{ old('nik') }}"
                                id="nik"oninput="extractDateAndCalculateAge()" class="form-control"
                                placeholder="Masukan NIK" />
                            @error('nik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="name">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" id="tanggal_lahir"
                                class="form-control" placeholder="Masukan Nama" required />
                            @error('tgl_lahir')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="exampleFormControlSelect1" class="form-label">Kota Lahir</label>
                            <select class="form-select" name="kabkota_id" id="kabkota_id">
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
                            <label class="form-label" for="multicol-username">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" id="jenis_kelamin"
                                aria-label="Default select example" required>
                                <option selected disabled>{{ old('jenis_kelamin', 'Pilih Jenis Kelamin') }}</option>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-username">Usia</label>
                            <input type="number" value="{{ old('usia') }}" id="usia" name="usia"
                                class="form-control" placeholder="usia" />
                            @error('usia')
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
                            <label class="form-label" for="agent_id">Agent Tps</label>
                            <select class="form-select" name="agent_id" id="agent_id" required>

                            </select>
                            @error('agent_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tps_id">Tps</label>
                            <select class="form-select" name="tps_id" id="tps_id" required>

                            </select>
                            @error('tps_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-username">Status</label>
                            <select class="form-select" name="status" id="status"
                                aria-label="Default select example" required>
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
                            <input type="text" value="{{ old('keterangan') }}" name="keterangan"
                                id="multicol-username" class="form-control" placeholder="Keterangan" />
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
                $('#status').select2();
            });

            function extractDateAndCalculateAge() {
                const nik = document.getElementById("nik").value;

                if (nik.length >= 15) {
                    const day = parseInt(nik.substr(6, 2), 10);
                    const month = parseInt(nik.substr(8, 2), 10);
                    const year = parseInt(nik.substr(10, 2), 10);

                    const fullYear = year > 50 ? 1900 + year : 2000 + year;
                    const formattedDate = `${fullYear}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                    document.getElementById("tanggal_lahir").value = formattedDate;

                    const currentYear = new Date().getFullYear();
                    let age = currentYear - fullYear;

                    // Jika bulan adalah Desember (12), kurangi usia sebesar 1
                    if (month === 12) {
                        age -= 1;
                    }

                    document.getElementById("usia").value = age;
                } else {
                    document.getElementById("tanggal_lahir").value = '';
                    document.getElementById("usia").value = '';
                }
            }

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

                // Cek jika ada old value untuk kabkota_id
                var oldKabkotaId = "{{ old('kabkota_id') }}"; // Ambil nilai lama dari Blade
                console.log(oldKabkotaId);
                if (oldKabkotaId) {
                    // Jika ada nilai lama, set value di select2
                    $.ajax({
                        url: "{{ route('getKabkotaById', ':id') }}".replace(':id', oldKabkotaId),
                        type: 'POST', // Ubah menjadi POST
                        data: {
                            "_token": "{{ csrf_token() }}" // Tambahkan token CSRF ke dalam request
                        },
                        success: function(data) {
                            // Tambahkan opsi yang dipilih ke select2 secara manual
                            var option = new Option(data.nama, data.id, true, true);
                            $('#kabkota_id').append(option).trigger('change');
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // Untuk debugging jika ada error
                        }
                    });
                }
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

                var oldTpsId = "{{ old('tps_id') }}"; // Ambil nilai lama dari Blade
                if (oldTpsId) {
                    // Jika ada nilai lama, set value di select2
                    $.ajax({
                        url: "{{ route('getTpsById', ':id') }}".replace(':id', oldTpsId),
                        type: 'POST', // Ubah menjadi POST
                        data: {
                            "_token": "{{ csrf_token() }}" // Tambahkan token CSRF ke dalam request
                        },
                        success: function(data) {
                            let nama = data.tps + ' - ' + data.kelurahan_nama + ' - ' + data.kecamatan_nama
                            // Tambahkan opsi yang dipilih ke select2 secara manual
                            var option = new Option(nama, data.id, true, true);
                            $('#tps_id').append(option).trigger('change');
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // Untuk debugging jika ada error
                        }
                    });
                }
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
                                        text: item.nama + ' - ' + item.kelurahans.nama_kelurahan +
                                            ' - ' + item.kelurahans.kecamatans.nama
                                    }
                                })
                            };
                        },
                    },
                });
            });
        </script>
    @endsection
