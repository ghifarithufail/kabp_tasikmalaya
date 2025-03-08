@extends('main')
@section('content')
    <div class="card mb-4">
        <h5 class="card-header">TEAM Create</h5>
        <div class="card-body">
            <form action="{{ route('data/partai/update', $partai->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation @if ($errors->any()) was-validated @endif">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="nama" value="{{ $partai->nama }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan Nama" />
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="name">Foto</label>
                        <input type="file" name="foto" value="{{ $partai->foto }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan foto"/>
                        @error('foto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-12 mt-3">
                            @if($partai->foto != null && Storage::exists('uploads/' . $partai->foto))
                                <img src="{{ asset('uploads/' . $partai->foto) }}" width="80"
                                        style="border-radius: 20%;">
                            @else
                                <img src="{{ asset('img/img-placeholder.png') }}" width="80" style="border-radius: 20%;">
                            @endif
                        </div>
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
    </script>
@endsection
