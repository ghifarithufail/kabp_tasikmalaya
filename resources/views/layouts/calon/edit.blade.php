@extends('main')
@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Calon Walkot Create</h5>
        <div class="card-body">
            <form action="{{ route('data/calon/update', $data->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation ">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukan Nama"
                            value="{{ old('name', $data->name) }}" required
                            style="@if ($errors->has('name')) border-color:red; @endif" />
                        @error('name')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="partai">Tim</label>
                        <select type="text" name="partai[]" id="partai" class="form-control"
                            placeholder="Masukan Partai" style="@if ($errors->has('partai')) border-color:red; @endif"
                            multiple>
                            @foreach ($partais as $item)
                                <option value="{{ $item->id }}" @if (in_array($item->id, $selectedPartais)) selected @endif>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>

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
                            <option value="{{ $data->status }}" selected>
                                {{ old('status', \App\Models\Calon::STATUS[$data->status]) }}</option>
                            @if ($data->status == 0)
                                <option value="1">Aktif</option>
                            @else
                                <option value="0">Tidak Aktif</option>
                            @endif
                        </select>
                        @error('status')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="gambar">Gambar</label>
                        <input type="file" name="gambar" value="{{ $data->gambar }}"
                            id="bs-validation-gambar" class="form-control" placeholder="Masukan Gambar"
                            style="@if ($errors->has('gambar')) border-color:red; @endif" />
                        @error('gambar')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-12 mt-3">
                            @if ($data->gambar != null && File::exists('uploads/' . $data->gambar))
                                <img src="{{ asset('uploads/' . $data->gambar) }}" width="100"
                                    style="border-radius: 20%;">
                            @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="100"
                                    style="border-radius: 20%;">
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select id="kategori" name="kategori" class="selectpicker w-100" data-style="btn-default"
                            style="@if ($errors->has('kategori')) border-color:red; @endif" required>
                            <option disabled>Pilih kategori</option>
                            @foreach (App\Models\Calon::KATEGORI as $key => $value)
                                @if ($key == $data->kategori)
                                    <option value="{{ $key }}" selected>
                                        {{ $value }}
                                    </option>
                                @else
                                    <option value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('kategori')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6" id="daerah_pemilihan_container">
                        <label for="daerah_pemilihan" class="form-label">Daerah Pemilihan</label>
                        <select id="daerah_pemilihan" name="daerah_pemilihan" class="selectpicker w-100"
                            data-style="btn-default" style="@if ($errors->has('daerah_pemilihan')) border-color:red; @endif"
                            required>
                            <option selected disabled>{{ old('daerah_pemilihan', 'Pilih Daerah Pemilihan') }}</option>
                            @foreach (App\Models\Calon::DAERAH_PEMILIHAN as $key => $value)
                                @if ($key == $data->daerah_pemilihan)
                                    <option value="{{ $key }}" selected>
                                        {{ $value }}
                                    </option>
                                @else
                                    <option value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                @endif
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
        });
    </script>
@endsection
