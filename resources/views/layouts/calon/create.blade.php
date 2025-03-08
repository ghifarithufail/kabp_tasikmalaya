@extends('main')
@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Calon Create</h5>
        <div class="card-body">
            <form action="{{ route('data/calon/store') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation ">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukan Nama"
                            value="{{ old('name') }}" required
                            style="@if ($errors->has('name')) border-color:red; @endif" required />
                        @error('name')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="partai">Tim</label>
                        <select id="partai" name="partai[]" class="w-100" multiple data-style="btn-default"
                            style="@if ($errors->has('partai')) border-color:red; @endif">
                            <option disabled>{{ old('partai', 'Pilih Partai') }}</option>
                            @foreach ($partais as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach

                        </select>
                        {{-- <input type="text" name="partai" id="partai" class="form-control"
                            placeholder="Masukan Partai" value="{{ old('partai') }}"
                            style="@if ($errors->has('partai')) border-color:red; @endif" required /> --}}
                        @error('partai')
                            <div style="color:red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="selectpicker w-100" data-style="btn-default"
                            style="@if ($errors->has('status')) border-color:red; @endif" required>
                            <option selected disabled>{{ old('status', 'Pilih Status') }}</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="gambar">Gambar</label>
                        <input type="file" name="gambar" value="{{ old('gambar') }}"
                            id="bs-validation-gambar" class="form-control" placeholder="Masukan Gambar"
                            style="@if ($errors->has('gambar')) border-color:red; @endif" />
                        @error('gambar')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="kategori" class="form-label">kategori</label>
                        <select id="kategori" name="kategori" class="selectpicker w-100" data-style="btn-default"
                            style="@if ($errors->has('kategori')) border-color:red; @endif" required>
                            <option selected disabled>{{ old('kategori', 'Pilih kategori') }}</option>
                            @foreach (App\Models\Calon::KATEGORI as $key => $value)
                                <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6" id="daerah_pemilihan_container" style="display:none;">
                        <label for="daerah_pemilihan" class="form-label">Daerah Pemilihan</label>
                        <select id="daerah_pemilihan" name="daerah_pemilihan" class="selectpicker w-100"
                            data-style="btn-default" style="@if ($errors->has('daerah_pemilihan')) border-color:red; @endif"
                            required>
                            <option selected disabled>{{ old('daerah_pemilihan', 'Pilih Daerah Pemilihan') }}</option>
                            @foreach (App\Models\Calon::DAERAH_PEMILIHAN as $key => $value)
                                <option value="{{ $key }}"
                                    {{ old('daerah_pemilihan') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('daerah_pemilihan')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="pt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                    {{-- <button type="reset" class="btn btn-label-secondary">Cancel</button> --}}
                </div>
            </form>
        </div>
    </div>

    <script>
        showLoading();
        $(document).ready(function() {
            hideLoading();
            $("#partai").select2({
                maximumSelectionLength: 10
            });

            let daerahPemilihan = document.getElementById('daerah_pemilihan');
            daerahPemilihan.style.display = 'none'

            document.getElementById('kategori').addEventListener('change', function() {
                var selectedValue = this.value;

                if (selectedValue) {
                    document.getElementById('daerah_pemilihan_container').style.display = 'block';
                } else {
                    document.getElementById('daerah_pemilihan_container').style.display = 'none';
                }
            });
        });
    </script>
@endsection
