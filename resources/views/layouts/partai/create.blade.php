@extends('main')
@section('content')
    <div class="card mb-4">
        <h5 class="card-header">TEAM Create</h5>
        <div class="card-body">
            <form action="{{ route('data/partai/store') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation @if ($errors->any()) was-validated @endif">
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
                    <div class="col-md-6">
                        <label class="form-label" for="name">Foto</label>
                        <input type="file" name="foto" value="{{ old('foto') }}" id="bs-validation-name"
                            class="form-control" placeholder="Masukan foto"/>
                        @error('foto')
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
    </script>
@endsection
